<?php

namespace App\Repository;

use App\Entity\TribCFOP;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method TribCFOP|null find($id, $lockMode = null, $lockVersion = null)
 * @method TribCFOP|null findOneBy(array $criteria, array $orderBy = null)
 * @method TribCFOP[]    findAll()
 * @method TribCFOP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TribCFOPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TribCFOP::class);
    }

    /**
     * @param Request|null $request
     * @param null $id
     * @param array $order ["coluna" => "asc"]
     * @return int|mixed|string
     * @throws NonUniqueResultException
     */
    public function fetch(Request $request, $id = null, array $order = [])
    {
        if ($id == '0'){
            return null;
        }
        $qb = $this->createQueryBuilder('tb')
            ->select('tb')
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

        foreach ($order as $k=>$r){
            $qb = $qb->addOrderBy($k, $r);
        }

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
