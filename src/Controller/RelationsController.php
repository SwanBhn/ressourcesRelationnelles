<?php

namespace App\Controller;

use App\Entity\Amis;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\AmisRepository;

class RelationsController extends AbstractController
{
    public function __construct(AmisRepository $amisRepository)
    {
        $this->amisRepository = $amisRepository;
    }

    #[Route('/relations', name: 'app_getRelations')]
    public function relations(EntityManagerInterface $entityManager): Response
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

            $amis = $this->amisRepository->findFriendsByUserId($userId);

            $currentUser = $entityManager->getRepository(User::class)->find($userId);
            $nom = $currentUser ? $currentUser->getNom() : null;

            $tableau = [];
            if(is_null($amis)){
                return $this->render('relations/relations.html.twig', [
                    'relations' => $tableau,
                    'nomUtilisateur' => $nom,
                    'erreur' => "Ajoutez des personnes à vos relations!"
                ]);
            }
            else{
                foreach ($amis as $ami) {
                    $amiId = $ami->getIdUtilisateurAmi()->getId();
                    if($amiId == $userId){
                        $tableau[] = [
                            'idUtilisateur' => $ami->getIdUtilisateurAmi()->getId(),
                            'imageAmi' => $ami->getIdUtilisateur()->getPhoto(),
                            'idAmi' => $ami->getIdUtilisateur()->getId(),
                            'nomAmi' => $ami->getIdUtilisateur()->getNom(),
                            'descriptionAmi' => $ami->getIdUtilisateur()->getDescription()
                        ];
                    }
                    else
                    {
                        $tableau[] = [
                            'idUtilisateur' => $ami->getIdUtilisateur()->getId(),
                            'imageAmi' => $ami->getIdUtilisateurAmi()->getPhoto(),
                            'idAmi' => $ami->getIdUtilisateurAmi()->getId(),
                            'nomAmi' => $ami->getIdUtilisateurAmi()->getNom(),
                            'descriptionAmi' => $ami->getIdUtilisateurAmi()->getDescription()
                        ];
                    }
                }

                return $this->render('relations/relations.html.twig', [
                    'relations' => $tableau,
                    'nomUtilisateur' => $nom
                ]);
            }
        }
    }

    #[Route('/supprimerRelation/{idAmis}', name: 'app_deleteRelation')]
    public function deleteRelation(EntityManagerInterface $entityManager, $idAmis): Response
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
    }

    #[Route('/ajouterRelation/{idAmis}', name: 'app_postRelation')]
    public function postRelation(EntityManagerInterface $entityManager, $idAmis): Response
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

            $currentUser = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
            
            $newFriend = $entityManager->getRepository(User::class)->findOneBy(['id' => $idAmis]);
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
    
    //----- Api -----//

    #[Route('/api/relations/{userId}', name: 'app_api_getRelations', methods: ['GET'])]
    public function getRelationsApi(EntityManagerInterface $entityManager, int $userId): Response
    {
        if (is_null($userId)) {
            return new JsonResponse(['message' => 'Erreur lors de la récupération de votre compte'], Response::HTTP_NOT_FOUND);
        }
        else
        {
            $currentUser = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
            if(is_null($currentUser)){
                return new JsonResponse(['message' => 'Erreur lors de la récupération de votre compte'], Response::HTTP_NOT_FOUND);
            }
        }

        $amis = $this->amisRepository->findFriendsByUserId($userId);

        if(is_null($amis)){
            return new JsonResponse(['message' => 'Ajoutez des personnes à vos relations!'], Response::HTTP_NOT_FOUND);
        }

        $tableau = [];
        foreach ($amis as $ami) {
            $amiId = $ami->getIdUtilisateurAmi()->getId();
            if($amiId == $userId){
                $tableau[] = [
                    'idUtilisateur' => $ami->getIdUtilisateurAmi()->getId(),
                    'photo' => $ami->getIdUtilisateur()->getPhoto(),
                    'idAmi' => $ami->getIdUtilisateur()->getId(),
                    'nom' => $ami->getIdUtilisateur()->getNom(),
                    'description' => $ami->getIdUtilisateur()->getDescription()
                ];
            }
            else
            {
                $tableau[] = [
                    'idUtilisateur' => $ami->getIdUtilisateur()->getId(),
                    'photo' => $ami->getIdUtilisateurAmi()->getPhoto(),
                    'idAmi' => $ami->getIdUtilisateurAmi()->getId(),
                    'nom' => $ami->getIdUtilisateurAmi()->getNom(),
                    'description' => $ami->getIdUtilisateurAmi()->getDescription()
                ];
            }
        }

        return new JsonResponse($tableau, 200, ['Access-Control-Allow-Origin' => '*']);
    }

    #[Route('/api/relations/{userId}/ami/{idAmi}', name: 'app_api_deleteRelations', methods: ['DELETE'])]
    public function deleteRelationsApi(EntityManagerInterface $entityManager, int $userId, int $idAmi): Response
    {
        if (is_null($userId)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        else
        {
            $currentUser = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
            if(is_null($currentUser)){
                return new Response('', Response::HTTP_NOT_FOUND);
            }
        }

        $amis = $this->amisRepository->findFriendsByUserId($userId);

        if(is_null($amis)){
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        
        $deleteAmis = $entityManager->getRepository(Amis::class)
        ->findOneBy([
            'idUtilisateur' => $userId,
            'idUtilisateurAmi' => $idAmi
        ]);

        if ($deleteAmis) {
            $entityManager->remove($deleteAmis);
            $entityManager->flush();

            return new Response('', Response::HTTP_OK);
        }
        else
        {
            $deleteAmis = $entityManager->getRepository(Amis::class)
            ->findOneBy([
                'idUtilisateur' => $idAmi,
                'idUtilisateurAmi' => $userId
            ]);

            if ($deleteAmis) {
                $entityManager->remove($deleteAmis);
                $entityManager->flush();

                return new Response('', Response::HTTP_OK);
            }
            else
            {
                return new Response('', Response::HTTP_NOT_FOUND);
            }
        }
    }
}