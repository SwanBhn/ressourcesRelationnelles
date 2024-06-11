<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ressources;
use App\Entity\Relations;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class StatisticController extends AbstractController
{
    #[Route('/statistic', name: 'app_statistic')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $ressources = $entityManager->getRepository(Ressources::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('statistic/index.html.twig', [
            'ressources' => $ressources,
            'users' => $users,
        ]);
    }
}
