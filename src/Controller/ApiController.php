<?php

namespace App\Controller;

use App\Adapter\AdapterInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiController implements ApiInterface
{
    /**
     * @var AdapterInterface
     */
    protected AdapterInterface $adapter;

    /**
     * @var null|ContainerInterface
     */
    protected ?ContainerInterface $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        if (!empty($this->adapter)){
            $this->adapter->setContainer($container);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->requestToArray($request);
        return $this->response($this->adapter->fetch($data));
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function show($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->response($this->adapter->fetch($data, $id));
    }

    /**
     * @param mixed $items
     * @return JsonResponse
     */
    protected function response($items): JsonResponse
    {
        $notify = $this->container->get('notify');
        if (is_array($items) && empty($items)){
            $notify->addMessage($notify::ERROR, "No data found");
        }
        $json = self::serialize(["items" => $items]);

        return JsonResponse::fromJsonString(
            $notify->newReturn($json), 200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @param Request $request
     * @return array|null
     */
    protected function requestToArray(Request $request): ?array
    {
        $post = json_decode($request->getContent(), true);
        $get = $request->query->all();
        $data = array_merge((array)$post, (array)$get);

        return array_merge($data, $request->query->all());
    }

    /**
     * @param $obj
     * @return string
     */
    public function serialize($obj): string
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $serializer = $this->container->get('jms_serializer');
        return $serializer->serialize($obj, 'json', $context);
    }
}