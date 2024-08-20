<?php
namespace App\Repository;

use App\Entity\Amis;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

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
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Amis::class);
        $this->entityManager = $entityManager;
    }

    public function findFriendsByUserId($userId)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder
            ->select('a')
            ->from(Amis::class, 'a')
            ->innerJoin(User::class, 'u', 'WITH', 'a.idUtilisateur = u.id')
            ->where('a.idUtilisateur = :userId OR a.idUtilisateurAmi = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('u.nom', 'ASC');

     

        return $queryBuilder->getQuery()->getResult();
    }
}

