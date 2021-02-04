<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\TribCST;
use App\Repository\TribCSTRepository;
use App\Service\Notify;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/cst")
 */
class TribCSTController extends ControllerController
{
    /**
     * TribCSTController constructor.
     * @param TribCSTRepository $repository
     * @param Notify $notify
     */
    public function __construct(TribCSTRepository $repository, Notify $notify)
    {
        $this->entity = TribCST::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_trib_cst_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ExceptionInterface
     */
    public function index(Request $request): JsonResponse
    {
        return $this->notifyReturn(
            parent::serialize(
                ["items" => $this->repository->fetch($request, null, ['tb.codigo' => 'asc'])]
            )
        );
    }

    /**
     * @Route("/list", name="_api_trib_cst_list", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function autocompleto(Request $request): JsonResponse
    {
        $cst = $this->repository->fetch($request);
        $temp = array();
        /**
         * @var $p TribCST
         */
        foreach ($cst as $p){
            $arr = array();
            $arr["text"] = $p->getCodigo().") ".substr($p->getNome(), 0, 40);
            $arr["value"] = $p->getId();
            $arr["label"] = $p->getNome();
            $temp[] = $arr;
        }

        return $this->notifyReturn(json_encode($temp));
    }

    /**
     * @Route("/{id}", name="api_trib_cst_show", methods={"GET"})
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
     * @Route("/{id}/edit", name="api_trib_cst_edit", methods={"POST"})
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
         * @var $item TribCST
         */
        $item = $this->createdEntity;
        $item->setCodigo($conteudo["codigo"]);
        @$item->setDescricao($conteudo["descricao"]);
        $item->setNome($conteudo["nome"]);
        $item->setTipo($conteudo["tipo"]);

        return $this->insertOrUpdate($validator, "CST");
    }
}