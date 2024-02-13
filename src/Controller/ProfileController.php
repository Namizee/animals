<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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
}
