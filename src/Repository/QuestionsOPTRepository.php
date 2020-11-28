<?php

namespace App\Repository;

use App\Entity\QuestionsOPT;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionsOPT|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionsOPT|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionsOPT[]    findAll()
 * @method QuestionsOPT[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsOPTRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionsOPT::class);
    }

    // /**
    //  * @return QuestionsOPT[] Returns an array of QuestionsOPT objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionsOPT
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
