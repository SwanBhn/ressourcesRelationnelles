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
        // Initialize default values
        $status = Response::HTTP_OK;
        $message = '';
        $data = [];

        // Check for invalid user ID
        if (!$this->isValidUserId($userId)) {
            $status = Response::HTTP_NOT_FOUND;
            $message = self::ERROR_MESSAGE;
        } else {
            $currentUser = $this->getCurrentUser($entityManager, $userId);

            if (!$currentUser) {
                $status = Response::HTTP_NOT_FOUND;
                $message = self::ERROR_MESSAGE;
            } else {
                $amis = $this->getFriends($userId);

                if (empty($amis)) {
                    $status = Response::HTTP_NOT_FOUND;
                    $message = 'Ajoutez des personnes à vos relations!';
                } else {
                    $data = $this->formatFriendsData($amis, $userId);
                }
            }
        }

        return new JsonResponse(
            $status === Response::HTTP_OK ? $data : ['message' => $message],
            $status,
            ['Access-Control-Allow-Origin' => '*']
        );
    }

    private function isValidUserId(?int $userId): bool
    {
        return !is_null($userId);
    }

    private function getCurrentUser(EntityManagerInterface $entityManager, int $userId): ?User
    {
        return $entityManager->getRepository(User::class)->find($userId);
    }

    private function getFriends(int $userId): array
    {
        return $this->amisRepository->findFriendsByUserId($userId) ?: [];
    }

    private function formatFriendsData(array $amis, int $userId): array
    {
        return array_map(function($ami) use ($userId) {
            $isCurrentUserFriend = $ami->getIdUtilisateurAmi()->getId() == $userId;
            
            return [
                'idUtilisateur' => $isCurrentUserFriend ? $ami->getIdUtilisateurAmi()->getId() : $ami->getIdUtilisateur()->getId(),
                'photo' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getPhoto() : $ami->getIdUtilisateurAmi()->getPhoto(),
                'idAmi' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getId() : $ami->getIdUtilisateurAmi()->getId(),
                'nom' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getNom() : $ami->getIdUtilisateurAmi()->getNom(),
                'description' => $isCurrentUserFriend ? $ami->getIdUtilisateur()->getDescription() : $ami->getIdUtilisateurAmi()->getDescription(),
            ];
        }, $amis);
    }

    

    #[Route('/api/relations/{userId}/ami/{idAmi}', name: 'app_api_deleteRelations', methods: ['DELETE'])]
    public function deleteRelationsApi(EntityManagerInterface $entityManager, int $userId, int $idAmi): Response
    {
        // Initialize default response status
        $status = Response::HTTP_OK;
        $message = '';
    
        // Check for valid userId
        if (is_null($userId) || !$this->isUserExists($entityManager, $userId)) {
            $status = Response::HTTP_NOT_FOUND;
            $message = 'User not found.';
        } else {
            // Check for existing friendship
            $deleteAmis = $this->findFriendshipToDelete($entityManager, $userId, $idAmi);
    
            if ($deleteAmis) {
                $entityManager->remove($deleteAmis);
                $entityManager->flush();
            } else {
                $status = Response::HTTP_NOT_FOUND;
                $message = 'Friendship not found.';
            }
        }
    
        // Return response with appropriate status and message
        return new Response($message, $status);
    }
    
    private function isUserExists(EntityManagerInterface $entityManager, int $userId): bool
    {
        return $entityManager->getRepository(User::class)->find($userId) !== null;
    }
    
    private function findFriendshipToDelete(EntityManagerInterface $entityManager, int $userId, int $idAmi): ?Amis
    {
        return $entityManager->getRepository(Amis::class)
            ->findOneBy([
                'idUtilisateur' => $userId,
                'idUtilisateurAmi' => $idAmi
            ]) ?? $entityManager->getRepository(Amis::class)
                ->findOneBy([
                    'idUtilisateur' => $idAmi,
                    'idUtilisateurAmi' => $userId
                ]);
    }    
}
