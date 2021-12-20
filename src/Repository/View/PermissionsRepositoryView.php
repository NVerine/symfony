<?php

namespace App\Repository\View;

use App\Entity\View\PermissionsView;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PermissionsView|null find($id, $lockMode = null, $lockVersion = null)
 * @method PermissionsView|null findOneBy(array $criteria, array $orderBy = null)
 * @method PermissionsView[]    findAll()
 * @method PermissionsView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissionsRepositoryView extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PermissionsView::class);
    }

    public function fetch(?array $data, $id)
    {
        $qb =
            $this->createQueryBuilder('tb');
        SQLHelper::setPagination($data, $qb);
        return $qb ->getQuery()
            ->getResult();
    }
}
