<?php

namespace App\Controller;

use App\Repository\AnimalCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(private AnimalCategoryRepository $animalCategoryRepository)
    {
    }

    #[Route(path: '/', name: 'home_index')]
    public function index(): Response
    {
        $categories = $this->animalCategoryRepository->findAll();

        return $this->render('home.html.twig', [
            'categories' => $categories,
        ]);
    }
}
