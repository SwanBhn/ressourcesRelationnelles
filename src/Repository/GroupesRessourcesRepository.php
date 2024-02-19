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

//    /**
//     * @return GroupesRessources[] Returns an array of GroupesRessources objects
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

//    public function findOneBySomeField($value): ?GroupesRessources
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
