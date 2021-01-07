<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\TribCFOP;
use App\Repository\TribCFOPRepository;
use App\Service\Notify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/cfop")
 */
class TribCFOPController extends ControllerController
{
    public function __construct(TribCFOPRepository $repository, Notify $notify)
    {
        $this->entity = TribCFOP::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_trib_cfop_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->notifyReturn(parent::lista($request, [], [], [], ['id', 'DESC']));
    }

    /**
     * @Route("/{id}", name="api_trib_cfop_show", methods={"GET"})
     */
    public function show($id): Response
    {
        return $this->notifyReturn(parent::single($id));
    }

    /**
     * @Route("/{id}/edit", name="api_trib_cfop_edit", methods={"POST"})
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
                ->getRepository(TribCFOP::class)
                ->find($id);
            //$notify->addMessage($notify::TIPO_INFO, "Atualizando cadastro de Familia de produto");
        }
        else {
            $item = new TribCFOP();
            //$notify->addMessage($notify::TIPO_INFO, "Adicionando registro no cadastro de Familia de produto");
        }
        $item->setCodigo($conteudo["codigo"]);
        $item->setDescricao($conteudo["descricao"]);

        $errors = $validator->validate($item);
        if (count($errors) > 0) {
            throw new \Exception($errors);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "CFOP salvo com sucesso");
        return $this->notifyReturn($item->getId());
    }
}