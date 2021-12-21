<?php


namespace App\Repository;


use Doctrine\ORM\QueryBuilder;

interface CustomRepository
{
    /**
     * @param $id
     * @param array|null $request
     */
    public function getOne($id, array $request = null);

    /**
     * @param array $request
     * @param QueryBuilder $qb
     * @return mixed
     */
    public function createWhere(array $request, QueryBuilder &$qb): void;
}