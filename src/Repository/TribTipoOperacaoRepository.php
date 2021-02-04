<?php

namespace App\Repository;

use App\Entity\TribTipoOperacao;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method TribTipoOperacao|null find($id, $lockMode = null, $lockVersion = null)
 * @method TribTipoOperacao|null findOneBy(array $criteria, array $orderBy = null)
 * @method TribTipoOperacao[]    findAll()
 * @method TribTipoOperacao[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TribTipoOperacaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TribTipoOperacao::class);
    }

    /**
     * @param Request|null $request
     * @param null $id
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function fetch(Request $request, $id = null)
    {
        $qb = $this->createQueryBuilder('tb')
            ->select('tb', 'cfop', 'cst_origem', 'cst_trib')
            ->innerJoin('tb.cfop', 'cfop', Join::WITH)
            ->innerJoin('tb.cst_origem', 'cst_origem', Join::WITH)
            ->innerJoin('tb.cst_trib', 'cst_trib', Join::WITH)
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
            ->getResult();
    }

    private function createWhere(Request $request, &$qb)
    {
        $conteudo = $request->query->all();

        if (isset($conteudo["pesq_tipo"]) && !empty($conteudo["pesq_tipo"])){
            $qb->where('tb.tipo = :tipo')->setParameter('tipo', $conteudo["pesq_tipo"]);
        }

        return $qb;
    }
}
