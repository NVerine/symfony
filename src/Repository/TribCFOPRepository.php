<?php

namespace App\Repository;

use App\Entity\TribCFOP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TribCFOP|null find($id, $lockMode = null, $lockVersion = null)
 * @method TribCFOP|null findOneBy(array $criteria, array $orderBy = null)
 * @method TribCFOP[]    findAll()
 * @method TribCFOP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TribCFOPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TribCFOP::class);
    }

    // /**
    //  * @return TribCFOPController[] Returns an array of TribCFOPController objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TribCFOPController
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
