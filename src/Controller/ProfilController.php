<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ressources;
use App\Entity\Enregistrer;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $currentUser = $entityManager->getRepository(User::class)->find($userId);
        $nom = $currentUser ? $currentUser->getNom() : null;
        $email = $currentUser ? $currentUser->getEmail() : null;

        $enregistrerRepository = $entityManager->getRepository(Enregistrer::class);
        $favoris = $enregistrerRepository->findBy(['idUtilisateur' => $userId]);

        $ressourceRepository = $entityManager->getRepository(Ressources::class);
        $result = [];

        // Récupérer les détails des ressources favorites
        foreach ($favoris as $favori) {
            $ressource = $ressourceRepository->find($favori->getIdRessource());
            if ($ressource !== null) {
                $result[] = [
                    'id' => $ressource->getId(),
                    'titre' => $ressource->getTitre(),
                    'contenu' => $ressource->getContenu(),
                    'dateCreation' => $ressource->getDateCreation(),
                    'estValidee' => $ressource->isEstValidee(),
                    'estRestreinte' => $ressource->isEstRestreinte(),
                    'estExploitee' => $ressource->isEstExploitee(),
                    'estArchivee' => $ressource->isEstArchivee(),
                    'estDesactivee' => $ressource->isEstDesactivee(),
                    'multimedia' => $ressource->getMultimedia(),
                    'idUtilisateur' => $ressource->getIdUtilisateur(),
                    'idCategorie' => $ressource->getIdCategorie(),
                    'commentaires' => $ressource->getCommentaires(),
                    'partages' => $ressource->getPartages(),
                    'enregistrers' => $ressource->getEnregistrers(),
                    'participers' => $ressource->getParticipers(),
                    'groupesRessources' => $ressource->getGroupesRessources(),
                ];
            }
        }

        return $this->render('profil/profil.html.twig', [
            'nomUtilisateur' => $nom, 
            'emailUtilisateur' => $email,
            'ressources' => $result
        ]);
    }

    //----- Api -----//

    #[Route('/api/ressources/favoris/{idUtilisateur}', name: 'app_api_ressources_favoris', methods: ['GET'])]
    public function getFavorisRessourcesApi(ManagerRegistry $doctrine, $idUtilisateur): Response
    {
        $em = $doctrine->getManager();
        
        // Récupérer les enregistrements favoris de l'utilisateur
        $enregistrerRepository = $em->getRepository(Enregistrer::class);
        $favoris = $enregistrerRepository->findBy(['idUtilisateur' => $idUtilisateur]);

        // Si aucun favori trouvé
        if (!$favoris) {
            return new JsonResponse(['message' => 'Aucun favori trouvé pour cet utilisateur!'], Response::HTTP_NOT_FOUND);
        }

        $ressourceRepository = $em->getRepository(Ressources::class);
        $result = [];

        // Récupérer les détails des ressources favorites
        foreach ($favoris as $favori) {
            $ressource = $ressourceRepository->find($favori->getIdRessource());
            if ($ressource !== null) {
                $result[] = [
                    'id' => $ressource->getId(),
                    'titre' => $ressource->getTitre(),
                    'contenu' => $ressource->getContenu(),
                    'dateCreation' => $ressource->getDateCreation(),
                    'estValidee' => $ressource->isEstValidee(),
                    'estRestreinte' => $ressource->isEstRestreinte(),
                    'estExploitee' => $ressource->isEstExploitee(),
                    'estArchivee' => $ressource->isEstArchivee(),
                    'estDesactivee' => $ressource->isEstDesactivee(),
                    'multimedia' => $ressource->getMultimedia(),
                    'idUtilisateur' => $ressource->getIdUtilisateur(),
                    'idCategorie' => $ressource->getIdCategorie(),
                    'commentaires' => $ressource->getCommentaires(),
                    'partages' => $ressource->getPartages(),
                    'enregistrers' => $ressource->getEnregistrers(),
                    'participers' => $ressource->getParticipers(),
                    'groupesRessources' => $ressource->getGroupesRessources(),
                ];
            }
        }

        return new JsonResponse($result);
    }
}
