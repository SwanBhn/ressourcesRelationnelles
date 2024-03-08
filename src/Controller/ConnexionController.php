<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

        // Récupérer l'utilisateur depuis la base de données
    //    $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['email' => $email]);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
    //    if ($utilisateur && $this->passwordEnco//der->isPasswordValid($utilisateur, $motDePasse)) {
            // Connecter l'utilisateur
     //       $token = new UsernamePasswordToken($utilisateur, null, 'main', $utilisateur->getRoles());
     //       $this->get('security.token_storage')->setToken($token);

     //       $this->addFlash('success', 'Connexion réussie ! Bienvenue, ' . $utilisateur->getNom() . ' !');

     //       return $this->render('accueil/index.html.twig', [
     //           'controller_name' => 'AccueilController',
     //       ]);
     //   } else {
            // Utilisateur non trouvé ou mot de passe incorrect
    //        $this->addFlash('error', 'Adresse e-mail ou mot de passe incorrect.');

           return $this->render('gererConnexion/gererConnexion.twig', [
                'controller_name' => 'ConnexionController',
            ]);
     //   }
    }
}