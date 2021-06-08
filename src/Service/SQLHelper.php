<?php

namespace App\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SQLHelper
 * @package App\Service
 */
class SQLHelper
{
    /**
     * @param Request $request
     * @param QueryBuilder $queryBuilder
     * @return string
     */
    public static function setPagination(Request $request, QueryBuilder $queryBuilder)
    {
        $limit = $request->query->get('pesq_limite');
        $pag = $request->query->get('pesq_offset');
        if (!empty($pag) && !empty($limit)) {
            $pag = $pag * $limit;
        }

        $queryBuilder->setFirstResult($pag)->setMaxResults($limit);

        return $queryBuilder;
    }

    /**
     * Retorna um array de resultados ou null
     * @param $qb
     * @return array|null
     */
    public static function getResultsOrNull($qb): ?array
    {
        $retorno = $qb->getQuery()
        ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
        ->getResult();
        if(empty($retorno)) return null;
        return $retorno;
    }
}