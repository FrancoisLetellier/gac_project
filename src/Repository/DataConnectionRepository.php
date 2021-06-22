<?php

namespace App\Repository;

use App\Entity\DataConnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataConnection|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataConnection|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataConnection[]    findAll()
 * @method DataConnection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataConnectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataConnection::class);
    }

    // /**
    //  * @return DataConnection[] Returns an array of DataConnection objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataConnection
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
