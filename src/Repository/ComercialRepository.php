<?php

namespace App\Repository;

use App\Entity\Comercial;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Comercial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comercial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comercial[]    findAll()
 * @method Comercial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comercial::class);
    }

    /**
     * @param Request|null $request
     * @param null $id
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function fetch(Request $request = null, $id = null)
    {
        $qb = $this->createQueryBuilder('comercial')
            ->select('pessoa', 'comercial', 'item', 'produto')
            ->innerJoin('comercial.cliente', 'pessoa')
            ->innerJoin('comercial.comercialItens', 'item')
            ->innerJoin('item.produto', 'produto')
        ;

        if($id > 0) {
            return $qb
                ->andWhere('comercial.id = :val')
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
