<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\Comercial;
use App\Entity\Filial;
use App\Entity\Pessoa;
use App\Repository\ComercialRepository;
use App\Service\Notify;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/comercial")
 */
class ComercialController extends ControllerController
{
    public function __construct(ComercialRepository $repository, Notify $notify)
    {
        $this->entity = Comercial::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_comercial_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch($request)));
    }

    /**
     * @Route("/{id}", name="api_comercial_show", methods={"GET"})
     * @param $id
     * @return JsonResponse
     * @throws ExceptionInterface|NonUniqueResultException
     */
    public function show($id): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch(null, $id)));
    }

    /**
     * @Route("/{id}/edit", name="api_comercial_edit", methods={"POST"})
     * @param $id
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
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

        $item->setPulaNf($conteudo["pulaNF"]);
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