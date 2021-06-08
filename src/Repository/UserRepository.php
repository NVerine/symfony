<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
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
            ->select('tb', 'ps', 'gp', 'fl')
            ->leftJoin('tb.grupo', 'gp')
            ->leftJoin('tb.pessoa', 'ps')
            ->leftJoin('tb.filiais', 'fl');
//            ->leftJoin('tb.contatoPrincipal', 'contatoPrincipal');

        if(!is_null($id)) {
            return $qb//->addSelect('endereco', 'contato')
//                ->leftJoin('tb.endereco', 'endereco')
//                ->leftJoin('tb.contato', 'contato')
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

    /**
     * @param Request $request
     * @param QueryBuilder $qb
     * @return mixed
     */
    private function createWhere(Request $request, QueryBuilder &$qb): QueryBuilder
    {
        $conteudo = $request->query->all();
        $qb->where('1 = 1');

        dump($request->query->all());

        if (isset($conteudo["username"]) && !empty($conteudo["username"])){
            $qb->andWhere('tb.username = :username')->setParameter('username', $conteudo["username"]);
        }

        return $qb;
    }

    /**
     * @param string $username
     * @return array|null
     */
    public function login(string $username){
        $qb = $this->createQueryBuilder('tb')
            ->select('tb', 'ps', 'gp', 'fl')
            ->leftJoin('tb.grupo', 'gp')
            ->leftJoin('tb.pessoa', 'ps')
            ->leftJoin('tb.filiais', 'fl')
            ->andWhere('tb.username = :username')
            ->setParameter('username', $username);

        return SQLHelper::getResultsOrNull($qb);
    }
}
