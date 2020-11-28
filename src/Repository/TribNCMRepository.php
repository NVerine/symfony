<?php

namespace App\Repository;

use App\Entity\TribNCM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TribNCM|null find($id, $lockMode = null, $lockVersion = null)
 * @method TribNCM|null findOneBy(array $criteria, array $orderBy = null)
 * @method TribNCM[]    findAll()
 * @method TribNCM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TribNCMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TribNCM::class);
    }

    // /**
    //  * @return TribNCM[] Returns an array of TribNCM objects
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
    public function findOneBySomeField($value): ?TribNCM
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
