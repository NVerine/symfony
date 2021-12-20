<?php

namespace App\Adapter;

use Exception;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

interface AdapterInterface extends ContainerAwareInterface
{
    /**
     * @param array|null $data
     * @param null $id
     * @return mixed
     */
    public function fetch(?array $data, $id = null);

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function save($id, $data);
}