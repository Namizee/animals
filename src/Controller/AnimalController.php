<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\AnimalCategoryRepository;
use App\Repository\AnimalRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    #[Route(path: '/categories/{page<\d+>?1}', name: 'animals_paginated', methods: ['GET'])]
    #[Route(path: '/categories/{slug}/{page<\d+>?1}', name: 'animals_category_paginated', methods: ['GET'])]
    public function categories(Request $request, int $page, AnimalRepository $animalRepository, AnimalCategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $categories = $categoryRepository->findAll();
        $category = null;

        if ($request->attributes->has('slug')) {
            $category = $categoryRepository->findOneBy(['slug' => $request->attributes->get('slug')]);
        }

        $pagination = $paginator->paginate(
            $animalRepository->findByCategory($category),
            $page,
            5
        );

        return $this->render('animal_categories.html.twig', [
            'pagination' => $pagination,
            'categories' => $categories,
            'category' => $category,
        ]);
    }

    #[Route('/animal/{id<\d+>}', name: 'animal')]
    public function animal(Animal $animal): Response
    {
        return $this->render('animal.html.twig', ['animal' => $animal]);
    }
}
