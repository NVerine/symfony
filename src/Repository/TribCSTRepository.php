<?php

namespace App\Repository;

use App\Entity\TribCST;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method TribCST|null find($id, $lockMode = null, $lockVersion = null)
 * @method TribCST|null findOneBy(array $criteria, array $orderBy = null)
 * @method TribCST[]    findAll()
 * @method TribCST[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TribCSTRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TribCST::class);
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
        $conteudo = $request->query->all();

        if (isset($conteudo["pesq_nome"]) && !empty($conteudo["pesq_nome"])){
            $qb->where('tb.nome LIKE :nome')->setParameter('nome', $conteudo["pesq_nome"]."%");
        }

        if (isset($conteudo["pesq_tipo"]) && !empty($conteudo["pesq_tipo"])){
            $qb->where('tb.tipo = :tipo')->setParameter('tipo', $conteudo["pesq_tipo"]);
        }

        return $qb;
    }
}
