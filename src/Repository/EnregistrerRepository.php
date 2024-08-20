<?php

namespace App\Repository;

use App\Entity\Enregistrer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enregistrer>
 *
 * @method Enregistrer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enregistrer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enregistrer[]    findAll()
 * @method Enregistrer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnregistrerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enregistrer::class);
    }

}
