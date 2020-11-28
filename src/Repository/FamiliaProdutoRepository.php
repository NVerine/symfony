<?php

namespace App\Repository;

use App\Entity\FamiliaProduto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FamiliaProduto|null find($id, $lockMode = null, $lockVersion = null)
 * @method FamiliaProduto|null findOneBy(array $criteria, array $orderBy = null)
 * @method FamiliaProduto[]    findAll()
 * @method FamiliaProduto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamiliaProdutoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FamiliaProduto::class);
    }

    // /**
    //  * @return FamiliaProduto[] Returns an array of FamiliaProduto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FamiliaProduto
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
