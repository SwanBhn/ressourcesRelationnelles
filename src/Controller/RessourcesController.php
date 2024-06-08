<?php

namespace App\Controller;
use App\Entity\Ressources;
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
  
    #[Route('/ressource/{id}', name: 'app_detailressources')]
    public function indexparId(RessourcesRepository $repository, $id)
    {
        
        $ressource = $repository->find($id);
       
        return $this->render('ressources/detailRessource.html.twig', [
            'ressource' => $ressource]);
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
