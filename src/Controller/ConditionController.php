<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConditionController extends AbstractController
{
    #[Route('/condition', name: 'app_condition')]
    public function index(): Response
    {
        return $this->render('condition/index.html.twig', [
            'controller_name' => 'ConditionController',
        ]);
    }
}
