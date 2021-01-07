<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\TribNCM;
use App\Repository\TribNCMRepository;
use App\Service\Notify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/ncm")
 */
class TribNCMController extends ControllerController
{
    public function __construct(TribNCMRepository $repository, Notify $notify)
    {
        $this->entity = TribNCM::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_trib_ncm_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->notifyReturn(parent::lista($request, ['default'], [], [], ['id', 'DESC']));
    }

    /**
     * @Route("/{id}", name="api_trib_ncm_show", methods={"GET"})
     */
    public function show($id): Response
    {
        return $this->notifyReturn(parent::single($id, ['default']));
    }

    /**
     * @Route("/{id}/edit", name="api_trib_ncm_edit", methods={"POST"})
     * @throws \Exception
     */
    public function edit($id, ValidatorInterface $validator, Request $request): Response
    {
        $conteudo = json_decode($request->getContent(), true);

        /**
         * @var $entityManager EntityManager
         */
        $entityManager = $this->getDoctrine()->getManager();

        if (!empty($id)) {
            $item = $this->getDoctrine()
                ->getRepository(TribNCM::class)
                ->find($id);
        }
        else {
            $item = new TribNCM();
        }

        $item->setCodigo(str_replace(".", "", $conteudo["codigo"]));
        $item->setDescricao($conteudo["descricao"]);
        $item->setNome($conteudo["nome"]);
        $item->setAliquota($conteudo["aliquota"]);

        $errors = $validator->validate($item);
        if (count($errors) > 0) {
            throw new \Exception($errors);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "NCM salvo com sucesso");
        return $this->notifyReturn($item->getId());
    }
}