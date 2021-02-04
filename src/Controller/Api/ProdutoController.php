<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\FamiliaProduto;
use App\Entity\Produto;
use App\Repository\ProdutoRepository;
use App\Service\Notify;
use App\Util\ValueHelper;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/produto")
 */
class ProdutoController extends ControllerController
{
    /**
     * atentar para ordenação
     * @var array
     */
    public static array $headers = [];

    /**
     * ProdutoController constructor.
     * @param ProdutoRepository $repository
     * @param Notify $notify
     */
    public function __construct(ProdutoRepository $repository, Notify $notify)
    {
        $this->entity = Produto::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_produto_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface|NonUniqueResultException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->notifyReturn(parent::serialize(["items" => $this->repository->fetch($request)]));
    }

    /**
     * @Route("/{id}", name="api_produto_show", methods={"GET"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     */
    public function show(Request $request, $id): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch($request, $id)));
    }

    /**
     * @Route("/dados/{id}", name="_api_produto_dados", methods={"GET"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     */
    public function dados(Request $request, $id): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch($request, $id)));
    }

    /**
     * @Route("/autocomplete/nome", name="_api_produto_autocomplete_nome", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function autocompleto(Request $request): JsonResponse
    {
        $produto = $this->repository->fetch($request);
        $temp = array();
        /**
         * @var $p Produto
         */
        foreach ($produto as $p){
            $arr = array();
            $arr["text"] = $p->getId()." | ".$p->getPreco()." | ".substr($p->getNome(), 0, 30);
            $arr["value"] = $p->getId();
            $arr["label"] = $p->getNome();
            $temp[] = $arr;
        }

        return $this->notifyReturn(json_encode($temp));
    }

    /**
     * @Route("/{id}/edit", name="api_produto_edit", methods={"POST"})
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

        $familia = $this->getDoctrine()
            ->getRepository(FamiliaProduto::class)
            ->find($conteudo["familia"]["id"]);

        /**
         * @var $item Produto
         */
        $item = $this->createdEntity;
        $item->setNome($conteudo["nome"]);
        $item->setPreco(ValueHelper::moneyToFloat($conteudo["preco"]));
        $item->setDescription($conteudo["description"]);
        $item->setFamilia($familia);

        return $this->insertOrUpdate($validator, "Produto");
    }
}
