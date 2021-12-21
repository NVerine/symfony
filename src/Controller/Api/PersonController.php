<?php

namespace App\Controller\Api;

use App\Adapter\PersonAdapter;
use App\Controller\ApiController;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends ApiController
{
    /**
     * PessoaController constructor.
     * @param PersonAdapter $adapter
     */
    public function __construct(PersonAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->autocomplete($data));
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function edit($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $fallback = $this->adapter->save($id, $data);

        if($fallback){
            $notify = $this->container->get('notify');
            $notify->addMessage($notify::SUCCESS, "Success! Person saved");
        }
        return $this->response($fallback);
    }
}
