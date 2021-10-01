<?php

namespace App\Repository;

use App\Entity\WpUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WpUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method WpUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method WpUsers[]    findAll()
 * @method WpUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WpUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WpUsers::class);
    }

    // /**
    //  * @return WpUsers[] Returns an array of WpUsers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WpUsers
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
