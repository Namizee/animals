<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\User;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/animal/{id<\d+>}', name: 'profile_animal_show', methods: ['GET'])]
    #[isGranted('show', 'animal')]
    public function show(Animal $animal): Response
    {
        return $this->render('profile/animal/show.html.twig', ['animal' => $animal]);
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
