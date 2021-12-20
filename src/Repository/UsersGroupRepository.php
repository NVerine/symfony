<?php

namespace App\Repository;

use App\Entity\UsersGroup;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UsersGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersGroup[]    findAll()
 * @method UsersGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersGroup::class);
    }

    /**
     * @param Request $request
     * @param null $id
     * @param array $order ["coluna" => "asc"]
     * @return int|mixed|string
     * @throws NonUniqueResultException
     */
    public function fetch(Request $request, $id = null, array $order = [])
    {
        $qb = $this->createQueryBuilder('tb')
            ->select('tb.name, tb.id, count(permissions.id) as perm')
            ->leftJoin('tb.permissions', 'permissions');


        if(!is_null($id)) {
            return $qb
                ->addSelect('permissions')
                ->leftJoin('tb.permissions', 'permissions')
                ->andWhere('tb.id = :val')
                ->setParameter('val', $id)

                ->getQuery()
                ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
                ->getOneOrNullResult();
        }


        $qb->groupBy('tb.id, tb.name');
        $qb->orderBy('tb.id', 'DESC');
        $this->createWhere($request, $qb);

        return SQLHelper::getResultsOrNull(SQLHelper::setPagination($request, $qb));
    }

    private function createWhere(Request $request, &$qb)
    {
        return $qb;
    }
}
