<?php

namespace App\Controller;
use App\Entity\Ressources;
use App\Entity\User;
use App\Entity\Commentaires;
use App\Entity\Enregistrer;
use App\Repository\RessourcesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class RessourcesController extends AbstractController
{
    #[Route('/ressources', name: 'app_ressources')]
    public function getRessources(EntityManagerInterface $entityManager)
    {
        $ressources = $entityManager->getRepository(Ressources::class)->findAll();
        
        $user = $this->getUser();

        $utilisateurId = "";

        if(!is_null($user)){
            $userId = $user->getId();

            if(is_null($userId)){
                //TODO: rediriger sur la page d'erreur
                throw new Exception('Erreur lors de la récupération de votre compte');
            }
            else{
                //Ressource avec connexion
                $utilisateurId = $userId;
            }
        }

        return $this->render('ressources/ressources.html.twig', [
            'ressources' => $ressources,
            'utilisateurId' => $utilisateurId
        ]);
    }

    #[Route('/ressources/supprimer/{id}', name: 'app_supprimer_ressource', methods: ['DELETE'])]
    public function deleteRessource($id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $ressource = $entityManager->getRepository(Ressources::class)->find($id);

        if (!$ressource) {
            $errorMessage = 'La ressource ' . $ressource->GetNom() . ' n\'existe pas.';
            $this->addFlash('error', $errorMessage);
        } else {
            $entityManager->remove($ressource);
            $entityManager->flush();
            $this->addFlash('success', 'La ressource a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_ressources');
    }
  
    #[Route('/ressource/{id}', name: 'app_detailressources')]
    public function getRessource(EntityManagerInterface $entityManager, $id)
    {
        $estEnregistrer = false;
        
        $user = $this->getUser();

        $ressource = $entityManager->getRepository(Ressources::class)->find($id);

        $commentaires = $entityManager->getRepository(Commentaires::class)->findBy(['idRessource' => $id]);
        
        $commentairesTab = [];
        foreach ($commentaires as $commentaire) {
            $commentairesTab[] = [
                'idCommentaire' => $commentaire->getId(),
                'contenu' => $commentaire->getContenu(),
                'dateCreation' => $commentaire->getDateCreation(),
                'nomUtilisateur' => $commentaire->getIdUtilisateur()->getNom(),
                'photoUtilisateur' => $commentaire->getIdUtilisateur()->getPhoto()
            ];
        }

        if(is_null($user)){
            //Ressource sans connexion
            $utilisateur = null;
        }
        else{
            $userId = $user->getId();

            if(is_null($userId)){
                //TODO: rediriger sur la page d'erreur
                throw new Exception('Erreur lors de la récupération de votre compte');
            }
            else{
                //Ressource avec connexion
                $utilisateur = $entityManager->getRepository(User::class)->find($userId);

                $enregistrement = $entityManager->getRepository(Enregistrer::class)->findOneBy(['idUtilisateur' => $userId, 'idRessource' => $id]);

                if(!is_null($enregistrement)){
                    $estEnregistrer = true;
                }
            }
        }
                   
        return $this->render('ressources/detailRessource.html.twig', [
            'ressource' => $ressource,
            'utilisateur' => $utilisateur,
            'commentaires' => $commentairesTab,
            'estEnregistrer' => $estEnregistrer 
        ]);
    }

    //----- API -----//

    #[Route('/api/ressources', name: 'app_api_ressources', methods: ['GET'])]
    public function getRessourcesApi(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Ressources::class);
        $ressources = $repository->findAll();
        $tableau= [];
        foreach($ressources as $ressource) {
            $tableau[]=[
                'id'=>$ressource->getId(),
                'titre'=>$ressource->getTitre(),
                'contenu'=>$ressource->getContenu(),
                'dateCreation'=>$ressource->getDateCreation(),
                'estValidee'=>$ressource->isEstValidee(),
                'estRestreinte'=>$ressource->isEstRestreinte(),
                'estExploitee'=>$ressource->isEstExploitee(),
                'estArchivee'=>$ressource->isEstArchivee(),
                'estDesactivee'=>$ressource->isEstDesactivee(),
                'multimedia'=>$ressource->getMultimedia(),
                'idUtilisateur'=>$ressource->getIdUtilisateur(),
                'idCategorie'=>$ressource->getIdCategorie(),
                'commentaires'=>$ressource->getCommentaires(),
                'partages'=>$ressource->getPartages(),
                'enregistrers'=>$ressource->getEnregistrers(),
                'participers'=>$ressource->getParticipers(),
                'groupesRessources'=>$ressource->getGroupesRessources(),
            ];
        }
        return new JsonResponse($tableau, 200, ['Access-Control-Allow-Origin' => '*']);
    }

     #[Route('/api/ressources/{id}', name: 'app_api_ressources_id', methods: ['GET'])]
    public function getRessourceApi(ManagerRegistry $doctrine, $id): Response
    {
     $repository = $doctrine->getRepository(Ressources::class);
     $ressource = $repository->find($id);
     if ($ressource !== null) {
        $tableau=[
            'id'=>$ressource->getId(),
            'titre'=>$ressource->getTitre(),
            'contenu'=>$ressource->getContenu(),
            'dateCreation'=>$ressource->getDateCreation(),
            'estValidee'=>$ressource->isEstValidee(),
            'estRestreinte'=>$ressource->isEstRestreinte(),
            'estExploitee'=>$ressource->isEstExploitee(),
            'estArchivee'=>$ressource->isEstArchivee(),
            'estDesactivee'=>$ressource->isEstDesactivee(),
            'multimedia'=>$ressource->getMultimedia(),
            'idUtilisateur'=>$ressource->getIdUtilisateur(),
            'idCategorie'=>$ressource->getIdCategorie(),
            'commentaires'=>$ressource->getCommentaires(),
            'partages'=>$ressource->getPartages(),
            'enregistrers'=>$ressource->getEnregistrers(),
            'participers'=>$ressource->getParticipers(),
            'groupesRessources'=>$ressource->getGroupesRessources(),
        ];
         return new JsonResponse($tableau);
        } else {
            return new JsonResponse(['message' => 'La ressource est introuvable!'], Response::HTTP_NOT_FOUND);
        }
    }
}
