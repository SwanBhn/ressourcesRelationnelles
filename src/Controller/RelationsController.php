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
    private const ERROR_MESSAGE = 'Erreur lors de la récupération de votre compte';

    private $amisRepository;

    public function __construct(AmisRepository $amisRepository)
    {
        $this->amisRepository = $amisRepository;
    }

    #[Route('/relations', name: 'app_getRelations')]
    public function relations(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (is_null($user)) {
            return $this->redirectToRoute('app_register');
        }

        $userId = $user->getId();

        if (is_null($userId)) {
            throw new Exception(self::ERROR_MESSAGE);
        }

        $amis = $this->amisRepository->findFriendsByUserId($userId);
        $currentUser = $entityManager->getRepository(User::class)->find($userId);
        $nom = $currentUser ? $currentUser->getNom() : null;

        $relations = $this->prepareRelations($amis, $userId);

        return $this->render('relations/relations.html.twig', [
            'relations' => $relations,
            'nomUtilisateur' => $nom
        ]);
    }

    private function prepareRelations(array $amis, int $userId): array
    {
        $relations = [];

        foreach ($amis as $ami) {
            $relations[] = $this->formatRelation($ami, $userId);
        }

        return $relations;
    }

    private function formatRelation(Amis $ami, int $userId): array
    {
        $isCurrentUserFriend = $ami->getIdUtilisateurAmi()->getId() == $userId;

        return [
            'idUtilisateur' => $isCurrentUserFriend ? $ami->getIdUtilisateurAmi()->getId() : $ami->getIdUtilisateur()->getId(),
            'imageAmi' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getPhoto() : $ami->getIdUtilisateurAmi()->getPhoto(),
            'idAmi' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getId() : $ami->getIdUtilisateurAmi()->getId(),
            'nomAmi' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getNom() : $ami->getIdUtilisateurAmi()->getNom(),
            'descriptionAmi' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getDescription() : $ami->getIdUtilisateurAmi()->getDescription(),
        ];
    }

    #[Route('/supprimerRelation/{idAmis}', name: 'app_deleteRelation')]
    public function deleteRelation(EntityManagerInterface $entityManager, $idAmis): Response
    {
        $user = $this->getUser();

        if (is_null($user)) {
            return $this->redirectToRoute('app_register');
        }

        $userId = $user->getId();

        if (is_null($userId)) {
            throw new Exception(self::ERROR_MESSAGE);
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

    #[Route('/ajouterRelation/{idAmis}', name: 'app_postRelation')]
    public function postRelation(EntityManagerInterface $entityManager, $idAmis): Response
    {
        $user = $this->getUser();

        if (is_null($user)) {
            return $this->redirectToRoute('app_register');
        }

        $userId = $user->getId();

        if (is_null($userId)) {
            throw new Exception(self::ERROR_MESSAGE);
        }

        $currentUser = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);

        $newFriend = $entityManager->getRepository(User::class)->findOneBy(['id' => $idAmis]);
        $addAmis = new Amis();
        $addAmis->setIdUtilisateur($currentUser);
        $addAmis->setIdUtilisateurAmi($newFriend);

        $entityManager->persist($addAmis);
        $entityManager->flush();

        return $this->redirectToRoute('app_getRelations');
    }

    //----- Api -----//

    #[Route('/api/relations/{userId}', name: 'app_api_getRelations', methods: ['GET'])]
    public function getRelationsApi(EntityManagerInterface $entityManager, int $userId): Response
    {
        if (is_null($userId)) {
            return new JsonResponse(['message' => self::ERROR_MESSAGE], Response::HTTP_NOT_FOUND);
        }
    
        $currentUser = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
    
        if (is_null($currentUser)) {
            return new JsonResponse(['message' => self::ERROR_MESSAGE], Response::HTTP_NOT_FOUND);
        }
    
        $amis = $this->amisRepository->findFriendsByUserId($userId);
    
        if (is_null($amis)) {
            return new JsonResponse(['message' => 'Ajoutez des personnes à vos relations!'], Response::HTTP_NOT_FOUND);
        }
    
        $tableau = array_map(function($ami) use ($userId) {
            $isCurrentUserFriend = $ami->getIdUtilisateurAmi()->getId() == $userId;
            
            return [
                'idUtilisateur' => $isCurrentUserFriend ? $ami->getIdUtilisateurAmi()->getId() : $ami->getIdUtilisateur()->getId(),
                'photo' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getPhoto() : $ami->getIdUtilisateurAmi()->getPhoto(),
                'idAmi' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getId() : $ami->getIdUtilisateurAmi()->getId(),
                'nom' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getNom() : $ami->getIdUtilisateurAmi()->getNom(),
                'description' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getDescription() : $ami->getIdUtilisateurAmi()->getDescription(),
            ];
        }, $amis);
    
        return new JsonResponse($tableau, 200, ['Access-Control-Allow-Origin' => '*']);
    }
    


    #[Route('/api/relations/{userId}/ami/{idAmi}', name: 'app_api_deleteRelations', methods: ['DELETE'])]
    public function deleteRelationsApi(EntityManagerInterface $entityManager, int $userId, int $idAmi): Response
    {
        $response = new Response('', Response::HTTP_NOT_FOUND);

        if (!is_null($userId)) {
            $currentUser = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);

            if ($currentUser) {
                $amis = $this->amisRepository->findFriendsByUserId($userId);

                if ($amis) {
                    $deleteAmis = $entityManager->getRepository(Amis::class)
                        ->findOneBy([
                            'idUtilisateur' => $userId,
                            'idUtilisateurAmi' => $idAmi
                        ]);

                    if (!$deleteAmis) {
                        $deleteAmis = $entityManager->getRepository(Amis::class)
                            ->findOneBy([
                                'idUtilisateur' => $idAmi,
                                'idUtilisateurAmi' => $userId
                            ]);
                    }

                    if ($deleteAmis) {
                        $entityManager->remove($deleteAmis);
                        $entityManager->flush();
                        $response = new Response('', Response::HTTP_OK);
                    }
                }
            }
        }

        return $response;
    }
}
