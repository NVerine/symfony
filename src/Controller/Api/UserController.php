<?php

namespace App\Controller\Api;

use App\Adapter\UserAdapter;
use App\Controller\ApiController;
use App\Exception\BusinessException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UserController extends ApiController
{
    /**
     * UserController constructor.
     * @param UserAdapter $adapter
     */
    public function __construct(UserAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @throws Exception|ExceptionInterface
     */
    public function edit($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->save($id, $data));
    }

    /**
     * @throws BusinessException|ExceptionInterface
     */
    public function accountEdit($id, Request $request): JsonResponse
    {
        if (!($id > 0)) {
            throw new BusinessException("Impossible to insert a new account here");
        }
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->save($id, $data));
    }
}