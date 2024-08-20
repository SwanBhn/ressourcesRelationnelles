<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Ressources;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentairesController extends AbstractController
{
    #[Route("/ressources/{id}/commentaire", name: "app_add_commentaire", methods: ["POST"])]
    public function postCommentaire(Request $request, Ressources $ressource, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if(is_null($user)){
            //Redirige sur la page d'inscription
            return $this->redirectToRoute('app_register');
        }
        else{
            $commentaire = new Commentaires();

            $commentaire->setContenu($request->request->get('contenu'));
            $commentaire->setIdRessource($ressource);
            $commentaire->setIdUtilisateur($user);
            $commentaire->setDateCreation(new \DateTime());
            $entityManager->persist($commentaire);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_detailressources', ['id' => $ressource->getId()]);
        }
    }

    #[Route("/ressources/commentaire/{idCommentaire}", name: "app_delete_commentaire", methods: ["DELETE"])]
    public function deleteCommentaire($idCommentaire, EntityManagerInterface $entityManager): RedirectResponse
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
                $commentaire = $entityManager->getRepository(Commentaires::class)->find($idCommentaire);

                if ($commentaire) {
                    $entityManager->remove($commentaire);
                    $entityManager->flush();
                }
                
                return $this->redirectToRoute('app_detailressources', ['id' => $commentaire->getIdRessource()->getId()]);
            }
        }
    }
}
