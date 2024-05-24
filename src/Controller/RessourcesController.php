<?php

namespace App\Controller;
use App\Entity\Ressources;
use App\Entity\Commentaires;
use App\Entity\Utilisateurs;
use App\Repository\RessourcesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;

class RessourcesController extends AbstractController
{
    #[Route('/ressources', name: 'app_ressources')]
    public function getRessources(EntityManagerInterface $entityManager)
    {
        $ressources = $entityManager->getRepository(Ressources::class)->findAll();
        
        return $this->render('ressources/index.html.twig', [
            'ressources' => $ressources]);
    }

    #[Route('/ressource/{idRessource}', name: 'app_detailressources')]
    public function indexparId(EntityManagerInterface $entityManager, $idRessource)
    {
        // TODO: Connecter avec la connexion
        $utilisateur = $entityManager->getRepository(Utilisateurs::class)->find(1);
        
        $ressource = $entityManager->getRepository(Ressources::class)->find($idRessource);

        $commentaires = $entityManager->getRepository(Commentaires::class)->findBy(['idRessource' => $idRessource]);
        
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
       
        return $this->render('ressources/detailRessource.html.twig', [
            'ressource' => $ressource,
            "utilisateur" => $utilisateur,
            'commentaires' => $commentairesTab
        ]);
    }

    //-----Routes API-----//

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
 public function getRessource(ManagerRegistry $doctrine, $id): Response
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
