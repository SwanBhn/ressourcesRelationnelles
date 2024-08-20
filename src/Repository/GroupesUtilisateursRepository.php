<?php

namespace App\Repository;

use App\Entity\GroupesUtilisateurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupesUtilisateurs>
 *
 * @method GroupesUtilisateurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupesUtilisateurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupesUtilisateurs[]    findAll()
 * @method GroupesUtilisateurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupesUtilisateursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupesUtilisateurs::class);
    }

}
