<?php

namespace App\Repository\View;

use App\Entity\View\PersonView;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonView|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonView|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonView[]    findAll()
 * @method PersonView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepositoryView extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonView::class);
    }

    public function fetch(?array $data, ?string $id = null)
    {
        $qb = $this->createQueryBuilder('tb');
        SQLHelper::setPagination($data, $qb);
        return $qb ->getQuery()
            ->getResult();
    }
}
