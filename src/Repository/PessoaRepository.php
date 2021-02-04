<?php

namespace App\Repository;

use App\Entity\Pessoa;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Pessoa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pessoa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pessoa[]    findAll()
 * @method Pessoa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PessoaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pessoa::class);
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
            ->select('tb', 'endereco', 'contato')
            ->leftJoin('tb.endereco', 'endereco')
            ->leftJoin('tb.contato', 'contato')
//            ->innerJoin('item.produto', 'produto')
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

        return SQLHelper::setPagination($request, $qb)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult()
        ;
    }

    private function createWhere(Request $request, &$qb)
    {
        $conteudo = $request->query->all();

        if (isset($conteudo["pesq_nome"]) && !empty($conteudo["pesq_nome"])){
            $qb->where('tb.nome LIKE :nome')->setParameter('nome', "%".$conteudo["pesq_nome"]."%");
        }

        return $qb;
    }
}
