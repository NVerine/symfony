<?php

namespace App\Controller\Api;

use App\Adapter\BranchAdapter;
use App\Controller\ApiController;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class BranchController extends ApiController
{
    /**
     * BranchController constructor.
     * @param BranchAdapter $adapter
     */
    public function __construct(BranchAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function list(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->fetch($data));
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|ExceptionInterface
     */
    public function edit($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->save($id, $data));
    }
}