<?php

namespace App\Repository;

use App\Entity\Filial;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Filial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Filial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Filial[]    findAll()
 * @method Filial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Filial::class);
    }

    /**
     * @return Filial[] Returns an array of Filial objects
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadLoggedFilial($value)
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'p', 'c', 'e')
            ->innerJoin('f.pessoa', 'p', Join::WITH)
            ->innerJoin('p.contatoPrincipal', 'c', Join::WITH)
            ->innerJoin('p.enderecoPrincipal', 'e', Join::WITH)
            ->andWhere('f.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param Request|null $request
     * @param null $id
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function fetch(Request $request = null, $id = null)
    {
        $qb = $this->createQueryBuilder('tb')
//            ->select('pessoa', 'comercial', 'item', 'produto')
//            ->innerJoin('comercial.cliente', 'pessoa')
//            ->innerJoin('comercial.comercialItens', 'item')
//            ->innerJoin('item.produto', 'produto')
        ;

        if($id > 0) {
            return $qb
                ->andWhere('tb.id = :val')
                ->setParameter('val', $id)
                ->getQuery()
                ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
                ->getOneOrNullResult();
        }

        $this->createWhere($request, $qb);

        return SQLHelper::setPagination($request, $qb)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();
    }

    private function createWhere(Request $request, &$qb)
    {
//        $qb->andWhere('f.id = :val')
//            ->setParameter('val', $value)
        return $qb;
    }
}
