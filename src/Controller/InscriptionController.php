<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/inscription', name: 'inscription')]
    public function setInscription(Request $request): Response
    {
        // Récupérer les données du formulaire
        $nomUtilisateur = $request->request->get('nomUtilisateur');
        $email = $request->request->get('email');
        $motDePasse = $request->request->get('motDePasse');

        // Vérifier que tous les champs sont remplis
        if (empty($nomUtilisateur) || empty($email) || empty($motDePasse)) {
            $this->addFlash('error', 'Veuillez remplir tous les champs du formulaire.');
            return $this->render('gererConnexion/gererConnexion.twig', [
                'controller_name' => 'InscriptionController',
            ]);
        }

        $utilisateur = new Utilisateurs();
        $utilisateur->setNom($nomUtilisateur);
        $utilisateur->setEmail($email);
        $utilisateur->setMotDePasse($motDePasse);

        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush();

        $this->addFlash('success', 'Inscription réussie ! Bienvenue, ' . $nomUtilisateur . '!');

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
}