<?php

namespace App\Repository;

use App\Entity\GroupesRessources;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupesRessources>
 *
 * @method GroupesRessources|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupesRessources|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupesRessources[]    findAll()
 * @method GroupesRessources[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupesRessourcesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupesRessources::class);
    }

}
