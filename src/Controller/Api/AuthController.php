<?php

namespace App\Controller\Api;

use App\Adapter\UserAdapter;
use App\Controller\ApiController;
use App\Exception\BusinessException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class AuthController extends ApiController
{
    /**
     * @param UserAdapter $adapter
     */
    public function __construct(UserAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function index(Request $request): JsonResponse
    {
        return $this->response(null);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function show($id, Request $request): JsonResponse
    {
        return $this->response(null);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws BusinessException
     */
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->login($data, $request->headers->all()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function logoff(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->logout($data));
    }
}