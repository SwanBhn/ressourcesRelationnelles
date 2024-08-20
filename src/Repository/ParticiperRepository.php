<?php

namespace App\Repository;

use App\Entity\Participer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participer>
 *
 * @method Participer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participer[]    findAll()
 * @method Participer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticiperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participer::class);
    }

}
