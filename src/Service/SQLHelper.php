<?php

namespace App\Service;

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

        if (!empty($order)) {
            $queryBuilder->orderBy('t.' . $order[0], $order[1]);
        }

        $queryBuilder->setFirstResult($pag)->setMaxResults($limit);

        return $queryBuilder;
    }
}