<?php

namespace App\Controller;
use App\Entity\Categories;
use App\Entity\Commentaires;
use App\Entity\Enregistrer;
use App\Entity\Ressources;
use App\Entity\User;
use App\Form\RessourcePostFormType;
use App\Form\RessourcePutFormType;
use App\Repository\RessourcesRepository;
use Doctrine\ORM\EntityManagerInterface; 
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    #[Route('/ressources/creer', name: 'app_creer_ressource')]
    public function postRessource(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ressource = new Ressources();
        $form = $this->createForm(RessourcePostFormType::class, $ressource);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $currentDate = new \DateTime();
            $categorie = $entityManager->getRepository(Categories::class)->find(1);

            $ressource->setDateCreation($currentDate);
            $ressource->setEstPubliee(false);
            $ressource->setEstValidee(false);
            $ressource->setEstRestreinte(false);
            $ressource->setEstExploitee(true);
            $ressource->setEstArchivee(false);
            $ressource->setEstDesactivee(false);
            $ressource->setIdUtilisateur($this->getUser());
            $ressource->setIdCategorie($categorie);
            $entityManager->persist($ressource);
            $entityManager->flush();

            return $this->redirectToRoute('app_ressources');
        }

        return $this->render('ressources/creerRessource.html.twig', [
            'ressourcePostForm' => $form->createView(),
        ]);
    }

    #[Route('/ressources/editer/{id}', name: 'app_editer_ressource')]
    public function putRessource($id, Request $request, EntityManagerInterface $entityManager): Response
    {       
        $ressource = $entityManager->getRepository(Ressources::class)->find($id);

        $form = $this->createForm(RessourcePutFormType::class, $ressource);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $currentDate = new \DateTime();
            $categorie = $entityManager->getRepository(Categories::class)->find(1);
    
            $ressource->setDateCreation($currentDate);
            $ressource->setEstPubliee(false);
            $ressource->setEstValidee(false);
            $ressource->setEstRestreinte(false);
            $ressource->setEstExploitee(true);
            $ressource->setEstArchivee(false);
            $ressource->setEstDesactivee(false);
            $ressource->setIdUtilisateur($this->getUser());
            $ressource->setIdCategorie($categorie);
    
            $entityManager->flush();
    
            $redirectRoute = $request->query->get('redirect_to', 'app_ressources');
    
            return $this->redirectToRoute($redirectRoute);
        }
    
        return $this->render('ressources/editerRessource.html.twig', [
            'ressourcePutForm' => $form->createView(),
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
                'idUtilisateur' => $commentaire->getIdUtilisateur()->getId(),
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
