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

//    /**
//     * @return GroupesUtilisateurs[] Returns an array of GroupesUtilisateurs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GroupesUtilisateurs
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
