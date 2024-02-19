<?php

namespace App\Repository;

use App\Entity\Amis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Amis>
 *
 * @method Amis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Amis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Amis[]    findAll()
 * @method Amis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amis::class);
    }

//    /**
//     * @return Amis[] Returns an array of Amis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Amis
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
