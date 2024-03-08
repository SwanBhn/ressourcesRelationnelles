<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
    public function setConnexion(Request $request): Response
    {
        // Récupérer les données du formulaire
        $email = $request->request->get('email');
        $motDePasse = $request->request->get('motDePasse');

        // Vérifier que tous les champs sont remplis
        if (empty($email) || empty($motDePasse)) {
            $this->addFlash('error', 'Veuillez remplir tous les champs du formulaire.');
            return $this->render('gererConnexion/gererConnexion.twig', [
                'controller_name' => 'ConnexionController',
            ]);
        }

        $this->addFlash('success', 'Connexion réussie !');

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
}
