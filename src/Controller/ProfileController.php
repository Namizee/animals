<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\User;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use App\Service\ImageUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'profile_index', methods: ['GET'])]
    public function index(
        #[CurrentUser] User $user,
        AnimalRepository $animalRepository): Response
    {
        $userAnimals = $animalRepository->findBy(['user' => $user], ['publicationDate' => 'DESC']);

        return $this->render('profile/profile.html.twig', ['animals' => $userAnimals]);
    }

    #[Route('/animal/new', name: 'profile_animal_new', methods: ['GET', 'POST'])]
    public function new(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
        ImageUploadService $imageUploadService,
        LoggerInterface $logger
    ): Response {
        $form = $this->createForm(AnimalType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $uploadedImage = $form->get('uploadedImage')->getData();
                $newImageName = $imageUploadService->upload($uploadedImage);
                $animal = $form->getData();

                /* @var Animal $animal */

                $animal->setUser($user);
                $animal->setImage($newImageName);

                $entityManager->persist($animal);
                $entityManager->flush();

                $this->addFlash('success', 'Ваша запись успешно добавлена!');

                return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
            } catch (FileException $e) {
                $logger->error($e->getMessage(), ['e' => $e]);
                $this->addFlash('error', 'Не удалось загрузить файл. Попробуйте выполнить операция позже!');

                return $this->redirectToRoute('profile_animal_new', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('profile/animal/new.html.twig', ['form' => $form]);
    }

    #[Route('/animal/{id<\d+>}', name: 'profile_animal_show', methods: ['GET'])]
    #[isGranted('show', 'animal')]
    public function show(Animal $animal): Response
    {
        return $this->render('profile/animal/show.html.twig', ['animal' => $animal]);
    }

    #[Route('/animal/{id<\d+>}/edit', name: 'profile_animal_edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', 'animal')]
    public function edit(
        Animal $animal,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Ваша запись успешно добавлена!');

            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/animal/edit.html.twig', ['form' => $form]);
    }

    #[Route('/animal/{id<\d+>}/delete', name: 'profile_animal_delete', methods: ['POST'])]
    #[isGranted('delete', 'animal')]
    public function delete(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        $entityManager->remove($animal);
        $entityManager->flush();
        $this->addFlash('success', 'Ваша запись успешно удалена!');

        return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
