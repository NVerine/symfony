<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\TribCFOP;
use App\Repository\TribCFOPRepository;
use App\Service\Notify;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/cfop")
 */
class TribCFOPController extends ControllerController
{
    /**
     * TribCFOPController constructor.
     * @param TribCFOPRepository $repository
     * @param Notify $notify
     */
    public function __construct(TribCFOPRepository $repository, Notify $notify)
    {
        $this->entity = TribCFOP::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_trib_cfop_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->notifyReturn(
            parent::serialize(
                ["items" => $this->repository->fetch($request, null, ['tb.id' => 'desc'])]
            )
        );
    }

    /**
     * @Route("/list", name="_api_trib_cfop_list", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     */
    public function list(Request $request): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch($request, null, ['tb.codigo' => 'asc'])));
    }

    /**
     * @Route("/{id}", name="api_trib_cfop_show", methods={"GET"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ExceptionInterface
     */
    public function show(Request $request, $id): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch($request, $id)));
    }

    /**
     * @Route("/{id}/edit", name="api_trib_cfop_edit", methods={"POST"})
     * @param $id
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function edit($id, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $conteudo = json_decode($request->getContent(), true);
        $this->getOrCreate($id);


        /**
         * @var $item TribCFOP
         */
        $item = $this->createdEntity;
        $item->setCodigo($conteudo["codigo"]);
        $item->setDescricao($conteudo["descricao"]);

        return $this->insertOrUpdate($validator, "CFOP");
    }
}