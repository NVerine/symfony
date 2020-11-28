<?php

namespace App\Repository;

use App\Entity\Permissoes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Permissoes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Permissoes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Permissoes[]    findAll()
 * @method Permissoes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissoesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permissoes::class);
    }

    // /**
    //  * @return Permissoes[] Returns an array of Permissoes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Permissoes
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
