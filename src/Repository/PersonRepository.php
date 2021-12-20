<?php

namespace App\Repository;

use App\Entity\Person;
use App\Service\SQLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository implements CustomRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @param array|null $request
     * @param null $id
     */
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
            ->select('tb', 'address', 'contact', 'mainContact', 'mainAddress')
            ->leftJoin('tb.mainAddress', 'mainAddress')
            ->leftJoin('tb.mainContact', 'mainContact')
            ->leftJoin('tb.address', 'address')
            ->leftJoin('tb.contact', 'contact')
            ->where('tb.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, 1)
            ->getResult();
    }

    /**
     * @param array $request
     * @param QueryBuilder $qb
     * @return mixed
     */
    public function createWhere(array $request, QueryBuilder &$qb): void
    {
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
    }
}
