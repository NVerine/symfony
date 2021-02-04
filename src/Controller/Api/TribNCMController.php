<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\TribNCM;
use App\Repository\TribNCMRepository;
use App\Service\Notify;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/ncm")
 */
class TribNCMController extends ControllerController
{
    /**
     * TribNCMController constructor.
     * @param TribNCMRepository $repository
     * @param Notify $notify
     */
    public function __construct(TribNCMRepository $repository, Notify $notify)
    {
        $this->entity = TribNCM::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_trib_ncm_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ExceptionInterface
     */
    public function index(Request $request): JsonResponse
    {
        return $this->notifyReturn(
            parent::serialize(["items" =>$this->repository->fetch($request, null, ["tb.id" => "desc"])])
        );
    }

    /**
     * @Route("/{id}", name="api_trib_ncm_show", methods={"GET"})
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
     * @Route("/{id}/edit", name="api_trib_ncm_edit", methods={"POST"})
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
         * @var $item TribNCM
         */
        $item = $this->createdEntity;
        $item->setCodigo(str_replace(".", "", $conteudo["codigo"]));
        $item->setDescricao($conteudo["descricao"]);
        $item->setNome($conteudo["nome"]);
        $item->setAliquota($conteudo["aliquota"]);

        return $this->insertOrUpdate($validator, "NCM");
    }
}