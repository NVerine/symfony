<?php

namespace App\Repository;

use App\Entity\UsersGroup;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UsersGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersGroup[]    findAll()
 * @method UsersGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersGroupRepository extends ServiceEntityRepository implements CustomRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersGroup::class);
    }

    public function getOne($id, array $request = null)
    {
//        $addressFields = '{id, zip, address, addressComplement, city, deletedAt, district, number, uf}';
//        $contactFields = '{id, contactName, deletedAt, email, phone}';
//        ->select('partial tb.{id, name, birthDate, isActive, isCustomer, isEmployee, isSupplier, type, nickname, observations}',
//        'partial address.'.$addressFields,
//        'partial mainAddress.'.$addressFields,
//        'partial contact.'.$contactFields,
//        'partial mainContact.'.$contactFields)
        return $this->createQueryBuilder('tb')
            ->select('tb, permissions')
            ->leftJoin('tb.permissions', 'permissions')
            ->andWhere('tb.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();
    }

    public function createWhere(array $request, QueryBuilder &$qb): void
    {
        // TODO: Implement createWhere() method.
    }
}
