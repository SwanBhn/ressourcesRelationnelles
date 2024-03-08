<?php

namespace App\Controller;
use App\Repository\RessourcesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Ressource;


class RessourcesController extends AbstractController
{
    #[Route('/ressources', name: 'app_ressources')]
    public function index(RessourcesRepository $repository)
    {
        
        $ressources = $repository->findAll();
       
        return $this->render('ressources/index.html.twig', [
            'ressources' => $ressources]);
    }

   
}
