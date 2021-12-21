<?php

namespace App\Repository;

use App\Entity\Reports;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reports|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reports|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reports[]    findAll()
 * @method Reports[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reports::class);
    }

    /**
     * @return Reports[] Returns an array of Reports objects
     */
    public function fetch(string $name)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name = :name')
            ->setParameter('name', $name)
            ->orderBy('r.level', 'DESC')
            ->addOrderBy('r.columnOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $name
     * @return array
     */
    public function getColumnsOrder(string $name): array
    {
        $columns = $this->fetch($name);
        $arr = [];
        $level = 0;

        foreach ($columns as $r) {
            if ($level > $r->getLevel()) {
                break;
            }
            $level = $r->getLevel();
            $arr[] = $r;
        }
        return $arr;
    }
}
