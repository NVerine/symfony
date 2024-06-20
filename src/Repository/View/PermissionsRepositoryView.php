<?php

namespace App\Repository\View;

use App\Entity\View\PermissionsView;
use App\Repository\CustomRepository;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PermissionsView|null find($id, $lockMode = null, $lockVersion = null)
 * @method PermissionsView|null findOneBy(array $criteria, array $orderBy = null)
 * @method PermissionsView[]    findAll()
 * @method PermissionsView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissionsRepositoryView extends ServiceEntityRepository implements CustomRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PermissionsView::class);
    }

    public function fetch(?array $data)
    {
        $qb = $this->createQueryBuilder('tb');
        SQLHelper::setPagination($data, $qb);
        return $qb ->getQuery()->getResult();
    }

    public function createWhere(array $request, QueryBuilder &$qb): void
    {
        // TODO: Implement createWhere() method.
    }

    public function getOne($id, array $request = null)
    {
        // TODO: Implement getOne() method.
    }
}
