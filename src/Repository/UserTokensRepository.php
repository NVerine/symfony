<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserTokens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserTokens|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTokens|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTokens[]    findAll()
 * @method UserTokens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTokensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTokens::class);
    }

    /**
     * @param $token
     * @param $origin
     * @param $userAgent
     * @param $username
     * @return User[] Returns an array of UserTokens objects
     */
    public function getUserAuthenticated($token, $origin, $userAgent, $username)
    {
        // duração da sessão de 4 horas
        $date = new \DateTime();
        $date->modify('-4 hour');

        return $this->createQueryBuilder('t')
            ->leftJoin('t.user', 'u')->addSelect('u')
            ->andWhere('t.token = :token')
            ->andWhere('t.date > :date')
            ->andWhere('t.origin = :origin')
            ->andWhere('t.user_agent = :agent')
            ->andWhere('t.isActive = :active')
            ->andWhere('u.username = :username')
            ->setParameter('token', $token)
            ->setParameter('origin', $origin)
            ->setParameter('agent', $userAgent)
            ->setParameter('active', true)
            ->setParameter('username', $username)
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?UserTokens
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
