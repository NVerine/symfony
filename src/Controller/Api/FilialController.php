<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\Filial;
use App\Entity\Pessoa;
use App\Repository\FilialRepository;
use App\Service\Notify;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/filial")
 */
class FilialController extends ControllerController
{

    /**
     * atentar para ordenação
     * @var array
     */
    public static array $headers = [
        "id",
        ["nome" => "filial"],
        ["nomeRegime" => "regime"],
        "timezone",
        "pulaNf",
        "nomeFantasia",
        "razaosocial",
        "cnpj",
        ["enderecoCompleto" => "endereco"],
        ["contatoCompleto" => "contato"],

    ];

    /**
     * FilialController constructor.
     * @param FilialRepository $repository
     * @param Notify $notify
     */
    public function __construct(FilialRepository $repository, Notify $notify)
    {
        $this->entity = Filial::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_filial_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface|NonUniqueResultException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->notifyReturn(
            parent::serialize(
                ["headers" => self::$headers, "items" => $this->repository->fetch($request)],
                ["filial_default", "filial_index"]
            )
        );
    }

    /**
     * @Route("/{id}", name="api_filial_show", methods={"GET"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     */
    public function show($id, Request $request): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch($request, $id)));
//        return $this->notifyReturn(parent::single($id, [], [], ["pessoa" => "contato", "endereco", "user"]));
    }

    /**
     * @Route("/{id}/edit", name="api_filial_edit", methods={"POST"})
     * @throws \Exception
     */
    public function edit($id, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $conteudo = json_decode($request->getContent(), true);

        /**
         * @var $entityManager EntityManager
         */
        $entityManager = $this->getDoctrine()->getManager();

        if (!empty($conteudo["pessoa"]["id"])){
            $pessoa = $this->getDoctrine()
                ->getRepository(Pessoa::class)
                ->findOneBy(["id" => $conteudo["pessoa"]["id"], "empresa" => true]);
        }

        if (!empty($id)) {
            $item = $this->getDoctrine()
                ->getRepository(Filial::class)
                ->find($id);
        }
        else {
            $item = new Filial();

            if(!empty($pessoa->getFilial())){
                $this->notify->addMessage($this->notify::TIPO_ERROR, "Pessoa selecionada ja está vinculada à outra filial");
                return $this->notifyReturn("");
            }
        }

        if(empty($pessoa)){
            $this->notify->addMessage($this->notify::TIPO_ERROR, "Pessoa selecionada não é do tipo empresa");
            return $this->notifyReturn("");
        }

        $item->setPulaNf($conteudo["pulaNf"]);
        $item->setPessoa($pessoa);
        $item->setNome($conteudo["nome"]);
        $item->setRegimeTributario($conteudo["regimeTributario"]);
        $item->setTimezone($conteudo["timezone"]);

        $errors = $validator->validate($item);
        if (count($errors) > 0) {
            throw new \Exception($errors);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "Filial salva com sucesso");
        return $this->notifyReturn($item->getId());
    }
}