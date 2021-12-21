<?php

namespace App\Repository;

use App\Entity\Branch;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Branch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Branch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Branch[]    findAll()
 * @method Branch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BranchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Branch::class);
    }

    /**
     * @return Branch[] Returns an array of Filial objects
     * @throws NonUniqueResultException
     */
    public function loadLoggedFilial($value)
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'p', 'c', 'e')
            ->leftJoin('f.owner', 'p', Join::WITH)
            ->leftJoin('p.mainContact', 'c', Join::WITH)
            ->leftJoin('p.mainAddress', 'e', Join::WITH)
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
     * @throws NonUniqueResultException
     */
    public function fetch(Request $request = null, $id = null)
    {
        $qb = $this->createQueryBuilder('tb')
            ->select('tb', 'owner', 'mainContact', 'mainAddress')
            ->innerJoin('tb.owner', 'owner')
            ->innerJoin('owner.mainContact', 'mainContact')
            ->innerJoin('owner.mainAddress', 'mainAddress')
        ;

        if(!is_null($id)) {
            return $qb
                ->andWhere('tb.id = :val')
                ->setParameter('val', $id)
                ->getQuery()
                ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
                ->getOneOrNullResult();
        }

        $qb->orderBy('tb.id', 'DESC');

        $this->createWhere($request, $qb);
        return SQLHelper::getResultsOrNull(SQLHelper::setPagination($request, $qb));
    }

    private function createWhere(Request $request, &$qb)
    {
//        $qb->andWhere('f.id = :val')
//            ->setParameter('val', $value)
        return $qb;
    }
}
