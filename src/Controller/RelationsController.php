<?php

namespace App\Controller;

use App\Entity\Amis;
use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Repository\AmisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
            throw new UserNotFoundException();
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
        return array_map(fn($ami) => $this->formatRelation($ami, $userId), $amis);
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
            throw new UserNotFoundException();
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
            throw new UserNotFoundException();
        }

        $currentUser = $entityManager->getRepository(User::class)->find($userId);
        $newFriend = $entityManager->getRepository(User::class)->find($idAmis);

        if ($currentUser && $newFriend) {
            $addAmis = new Amis();
            $addAmis->setIdUtilisateur($currentUser);
            $addAmis->setIdUtilisateurAmi($newFriend);

            $entityManager->persist($addAmis);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_getRelations');
    }

    //----- Api -----//

    #[Route('/api/relations/{userId}', name: 'app_api_getRelations', methods: ['GET'])]
    public function getRelationsApi(EntityManagerInterface $entityManager, int $userId): Response
    {
        // Default response and status
        $response = new JsonResponse([], Response::HTTP_OK);
        $status = Response::HTTP_OK;
        $message = '';
    
        // Check for invalid user ID
        if (is_null($userId)) {
            $status = Response::HTTP_NOT_FOUND;
            $message = self::ERROR_MESSAGE;
        } else {
            $currentUser = $entityManager->getRepository(User::class)->find($userId);
    
            if (is_null($currentUser)) {
                $status = Response::HTTP_NOT_FOUND;
                $message = self::ERROR_MESSAGE;
            } else {
                $amis = $this->amisRepository->findFriendsByUserId($userId);
    
                if (is_null($amis) || count($amis) === 0) {
                    $status = Response::HTTP_NOT_FOUND;
                    $message = 'Ajoutez des personnes à vos relations!';
                } else {
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
    
                    $response = new JsonResponse($tableau, $status, ['Access-Control-Allow-Origin' => '*']);
                }
            }
        }
    
        return new JsonResponse(['message' => $message], $status, ['Access-Control-Allow-Origin' => '*']);
    }
    

    #[Route('/api/relations/{userId}/ami/{idAmi}', name: 'app_api_deleteRelations', methods: ['DELETE'])]
    public function deleteRelationsApi(EntityManagerInterface $entityManager, int $userId, int $idAmi): Response
    {
        if (is_null($userId)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $currentUser = $entityManager->getRepository(User::class)->find($userId);

        if (!$currentUser) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $amis = $this->amisRepository->findFriendsByUserId($userId);

        if (!$amis) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $deleteAmis = $entityManager->getRepository(Amis::class)
            ->findOneBy([
                'idUtilisateur' => $userId,
                'idUtilisateurAmi' => $idAmi
            ]) ?? $entityManager->getRepository(Amis::class)
                ->findOneBy([
                    'idUtilisateur' => $idAmi,
                    'idUtilisateurAmi' => $userId
                ]);

        if ($deleteAmis) {
            $entityManager->remove($deleteAmis);
            $entityManager->flush();
            return new Response('', Response::HTTP_OK);
        }

        return new Response('', Response::HTTP_NOT_FOUND);
    }
}
