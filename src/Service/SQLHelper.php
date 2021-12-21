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
     * @param array|null $data
     * @param QueryBuilder $queryBuilder
     */
    public static function setPagination(?array $data, QueryBuilder &$queryBuilder)
    {
        if(isset($data["search_limit"]) && !empty($data["search_limit"])){
            $queryBuilder->setMaxResults($data["search_limit"]);

            if(isset($data["search_offset"]) && !empty($data["search_offset"])){
                $queryBuilder->setFirstResult($data["search_offset"] * $data["search_limit"]);
            }
        }
    }

    /**
     * Retorna um array de resultados ou null
     * @param $qb
     * @return array|null
     */
    public static function getResultsOrNull($qb): ?array
    {
        $retorno = $qb->getQuery()
        ->getResult();
        if(empty($retorno)) return null;
        return $retorno;
    }
}