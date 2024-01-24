<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    #[Route(path: '/categories/{slug}', name: 'animal_categories')]
    public function categories(): Response
    {
        return $this->json('123');
    }
}
