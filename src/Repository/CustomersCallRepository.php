<?php

namespace App\Repository;

use App\Entity\CustomersCall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomersCall|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomersCall|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomersCall[]    findAll()
 * @method CustomersCall[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomersCallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomersCall::class);
    }

    public function callDurationReal() {
        return $this->createQueryBuilder('c')
            ->andWhere('c.call_date <= :date')
            ->setParameter('date', '2012-02-15')
            ->orderBy('c.call_date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return CustomersCall[] Returns an array of CustomersCall objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomersCall
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
