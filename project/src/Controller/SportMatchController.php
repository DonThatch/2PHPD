<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SportMatchController extends AbstractController
{
    #[Route('/sport/match', name: 'app_sport_match')]
    public function index(): Response
    {
        return $this->render('sport_match/index.html.twig', [
            'controller_name' => 'SportMatchController',
        ]);
    }
}
