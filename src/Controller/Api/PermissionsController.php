<?php

namespace App\Controller\Api;

use App\Adapter\PermissionsAdapter;
use App\Controller\ApiController;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class PermissionsController extends ApiController
{
    /**
     * PermissionsController constructor.
     * @param PermissionsAdapter $adapter
     */
    public function __construct(PermissionsAdapter $adapter)
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
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->getUsersGroup($data));
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function show($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->getUsersGroup($data, $id));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function listPermissions(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    /**
     * @return JsonResponse
     * @throws Exception|ExceptionInterface
     */
    public function getCurrentPermissions(): JsonResponse
    {
        return $this->response($this->adapter->getCurrentPermissions());
    }

    /**
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function listRoutes(): JsonResponse
    {
        return $this->response($this->adapter->mountRoutes());
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