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

//    /**
//     * @return Enregistrer[] Returns an array of Enregistrer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Enregistrer
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
