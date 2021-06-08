<?php

namespace App\Repository;

use App\Entity\Pessoa;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
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
            ->select('tb', 'enderecoPrincipal', 'contatoPrincipal')
            ->leftJoin('tb.enderecoPrincipal', 'enderecoPrincipal')
            ->leftJoin('tb.contatoPrincipal', 'contatoPrincipal');

        if(!is_null($id)) {
            return $qb->addSelect('endereco', 'contato')
                ->leftJoin('tb.endereco', 'endereco')
                ->leftJoin('tb.contato', 'contato')
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

        if (isset($conteudo["pesq_nome"]) && !empty($conteudo["pesq_nome"])){
            $qb->andWhere('tb.nome LIKE :nome')->setParameter('nome', "%".$conteudo["pesq_nome"]."%");
        }
        if (isset($conteudo["pesq_ativo"]) && $conteudo["pesq_ativo"] == "true"){
            $qb->andWhere('tb.ativo = :ativo')->setParameter('ativo', true);
        }
        if (isset($conteudo["pesq_cliente"]) && $conteudo["pesq_cliente"] == "true"){
            $qb->andWhere('tb.cliente = :cliente')->setParameter('cliente', true);
        }
        if (isset($conteudo["pesq_empresa"]) && $conteudo["pesq_empresa"] == "true"){
            $qb->andWhere('tb.empresa = :empresa')->setParameter('empresa', true);
        }
        if (isset($conteudo["pesq_fornecedor"]) && $conteudo["pesq_fornecedor"] == "true"){
            $qb->andWhere('tb.fornecedor = :fornecedor')->setParameter('fornecedor', true);
        }
        if (isset($conteudo["pesq_funcionario"]) && $conteudo["pesq_funcionario"] == "true"){
            $qb->andWhere('tb.funcionario = :funcionario')->setParameter('funcionario', true);
        }
        if (isset($conteudo["pesq_cpfCnpj"]) && !empty($conteudo["pesq_cpfCnpj"])){
            $qb->andWhere('tb.cpf_cnpj = :cpf')->setParameter('cpf', $conteudo["pesq_cpfCnpj"]);
        }

        return $qb;
    }
}
