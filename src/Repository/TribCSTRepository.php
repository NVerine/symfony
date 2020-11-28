<?php

namespace App\Repository;

use App\Entity\TribCST;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TribCST|null find($id, $lockMode = null, $lockVersion = null)
 * @method TribCST|null findOneBy(array $criteria, array $orderBy = null)
 * @method TribCST[]    findAll()
 * @method TribCST[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TribCSTRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TribCST::class);
    }

    // /**
    //  * @return TribCST[] Returns an array of TribCST objects
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
    public function findOneBySomeField($value): ?TribCST
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
