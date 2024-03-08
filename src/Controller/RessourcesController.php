<?php

namespace App\Controller;
use App\Repository\RessourcesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Ressource;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\HttpFoundation\RedirectResponse;

class RessourcesController extends AbstractController
{
    #[Route('/ressources', name: 'app_ressources')]
    public function index(RessourcesRepository $repository)
    {
        
        $ressources = $repository->findAll();
       
        return $this->render('ressources/index.html.twig', [
            'ressources' => $ressources]);
    }

    #[Route('/ressources/supprimer/{id}', name: 'app_supprimer_ressource', methods: ['POST'])]
    public function supprimerRessource($id, RessourcesRepository $repository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $ressource = $repository->find($id);

        if (!$ressource) {
            $errorMessage = 'La ressource avec l\'identifiant ' . $id . ' n\'existe pas.';
            $this->addFlash('error', $errorMessage);
        } else {
            $entityManager->remove($ressource);
            $entityManager->flush();
            $this->addFlash('success', 'La ressource a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_ressources');
    }
}
