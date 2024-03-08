<?php

namespace App\Controller;

use App\Entity\Amis;
use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RelationsController extends AbstractController
{
    #[Route('/Relations', name: 'app_getRelations')]
    public function relations(EntityManagerInterface $entityManager): Response
    {
        //TODO: Modifier pour recuperer l'id de l'utilisateur connecté
        $userId = 1;

        if (!$userId) {
            //TODO: Créer la page d'erreur pour cette route => return $this->redirectToRoute('app_Error');
            throw new Exception('Erreur lors de la récupération de votre compte');
        }

        $amis = $entityManager->getRepository(Amis::class)->findBy(['idUtilisateur' => $userId]);
        
        $tableau = [];
        foreach ($amis as $ami) {
            $tableau[] = [
                'idUtilisateur' => $ami->getIdUtilisateur(),
                'idAmi' => $ami->getIdUtilisateurAmi()->getId(),
                'nomAmi' => $ami->getIdUtilisateurAmi()->getNom(),
            ];
        }

        $currentUser = $entityManager->getRepository(Utilisateurs::class)->find($userId);
        $nom = $currentUser ? $currentUser->getNom() : null;

        return $this->render('relations/relations.html.twig', [
            'relations' => $tableau,
            'nomUtilisateur' => $nom
        ]);
    }

    #[Route('/SupprimerRelation/{idAmis}', name: 'app_deleteRelation')]
    public function deleteRelation(EntityManagerInterface $entityManager, $idAmis): Response
    {
        //TODO: Cf L18
        $userId = 1;

        if (!$userId) {
            //TODO: Cf L22
            throw new Exception('Erreur lors de la récupération de votre compte');
        }

        $deleteAmis = $entityManager->getRepository(Amis::class)
            ->findOneBy([
                'idUtilisateur' => $userId,
                'idUtilisateurAmi' => $idAmis
            ]);

        if ($deleteAmis) {
            $entityManager->remove($deleteAmis);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('app_getRelations');
    }

    #[Route('/AjouterRelation/{idAmis}', name: 'app_postRelation')]
    public function postRelation(EntityManagerInterface $entityManager, $idAmis): Response
    {
        //TODO: Cf L18
        $userId = 1;

        if (!$userId) {
            //TODO: Cf L22
            throw new Exception('Erreur lors de la récupération de votre compte');
        }

        $currentUser = $entityManager->getRepository(Utilisateurs::class)->findOneBy(['id' => $userId]);
        
        $newFriend = $entityManager->getRepository(Utilisateurs::class)->findOneBy(['id' => $idAmis]);
        $addAmis = new Amis();
        $addAmis->setIdUtilisateur($currentUser);
        $addAmis->setIdUtilisateurAmi($newFriend);

        //Ajout de la relation
        $entityManager->persist($addAmis);
        $entityManager->flush();
        
        //TODO: Changer la redirection vers le profil de l'utilisateur ajouté
        return $this->redirectToRoute('app_getRelations');
    }
}