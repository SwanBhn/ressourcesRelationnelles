<?php

namespace App\Controller;


use App\Entity\Enregistrer;
use App\Entity\Ressources;
use App\Entity\User;
use App\Repository\EnregistrerRepository;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class FavorisController extends AbstractController
{
    #[Route('/favoris/ajouter/{id}', name: 'app_add_favoris', methods: ['POST'])]
    public function ajouter($id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if(is_null($user)){
            //Redirige sur la page d'inscription
            return $this->redirectToRoute('app_register');
        }
        else{
            $userId = $user->getId();

            if(is_null($userId)){
                throw new Exception('Erreur lors de la récupération de votre compte');
            }
            else{
                $currentDate = new \DateTime();
                $currentUser = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
                
                $currentRessource = $entityManager->getRepository(Ressources::class)->findOneBy(['id' => $id]);

                $enregistrement = new Enregistrer();
                $enregistrement->setIdUtilisateur($currentUser);
                $enregistrement->setIdRessource($currentRessource);
                $enregistrement->setDateFavoris($currentDate);
        
                //Ajout de la ressource en favoris
                $entityManager->persist($enregistrement);
                $entityManager->flush();
                
                return $this->redirectToRoute('app_detailressources', array('id' => $id));
            }
        }        
    }

    #[Route('/favoris/supprimer/{id}', name: 'app_delete_favoris', methods: ['DELETE'])]
    public function supprimer($id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if(is_null($user)){
            //Redirige sur la page d'inscription
            return $this->redirectToRoute('app_register');
        }
        else{
            $userId = $user->getId();

            if(is_null($userId)){
                //TODO: rediriger sur la page d'erreur
                throw new Exception('Erreur lors de la récupération de votre compte');
            }
            else{
                $enregistrement = $entityManager->getRepository(Enregistrer::class)->findOneBy(['idUtilisateur' => $userId, 'idRessource' => $id]);

                if ($enregistrement) {
                    $entityManager->remove($enregistrement);
                    $entityManager->flush();
                }
        
                return $this->redirectToRoute('app_detailressources', array('id' => $id));
            }
        }
    }
}
