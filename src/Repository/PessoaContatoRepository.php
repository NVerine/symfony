<?php

namespace App\Repository;

use App\Entity\PessoaContato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PessoaContato|null find($id, $lockMode = null, $lockVersion = null)
 * @method PessoaContato|null findOneBy(array $criteria, array $orderBy = null)
 * @method PessoaContato[]    findAll()
 * @method PessoaContato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PessoaContatoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PessoaContato::class);
    }

    // /**
    //  * @return PessoaContato[] Returns an array of PessoaContato objects
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
    public function findOneBySomeField($value): ?PessoaContato
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
