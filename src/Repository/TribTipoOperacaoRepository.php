<?php

namespace App\Repository;

use App\Entity\TribTipoOperacao;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TribTipoOperacao|null find($id, $lockMode = null, $lockVersion = null)
 * @method TribTipoOperacao|null findOneBy(array $criteria, array $orderBy = null)
 * @method TribTipoOperacao[]    findAll()
 * @method TribTipoOperacao[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TribTipoOperacaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TribTipoOperacao::class);
    }

    // /**
    //  * @return TribTipoOperacao[] Returns an array of TribTipoOperacao objects
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
    public function findOneBySomeField($value): ?TribTipoOperacao
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
