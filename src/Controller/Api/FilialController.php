<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\Filial;
use App\Entity\Pessoa;
use App\Repository\FilialRepository;
use App\Service\Notify;
use App\Traits\Response;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/filial")
 */
class FilialController extends ControllerController
{
    use Response;

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
     * @throws NonUniqueResultException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->response(
            $this->repository->fetch($request),
            self::$headers,
            ["filial_default", "filial_index"]
        );
    }

    /**
     * @Route("/lista", name="api_filial_list", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function list(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    /**
     * @Route("/{id}", name="api_filial_show", methods={"GET"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function show($id, Request $request): JsonResponse
    {
        return $this->response(
            $this->repository->fetch($request, $id)
        );
    }

    /**
     * @Route("/{id}/edit", name="api_filial_edit", methods={"POST"})
     * @param $id
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
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

        if(empty($pessoa->getContatoPrincipal())){
            $this->notify->addMessage($this->notify::TIPO_ERROR, "Pessoa selecionada não é possui um contato principal");
            return $this->notifyReturn("");
        }

        if(empty($pessoa->getEnderecoPrincipal())){
            $this->notify->addMessage($this->notify::TIPO_ERROR, "Pessoa selecionada não é possui um endereço principal");
            return $this->notifyReturn("");
        }

        $item->setPulaNf($conteudo["pulaNf"]);
        $item->setSocio($pessoa);
        $item->setNome($conteudo["nome"]);
        $item->setRegimeTributario($conteudo["regimeTributario"]);
        $item->setTimezone($conteudo["timezone"]);

        $errors = $validator->validate($item);
        if (count($errors) > 0) {
            throw new Exception($errors);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "Filial salva com sucesso");
        return $this->notifyReturn($item->getId());
    }
}