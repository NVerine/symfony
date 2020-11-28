<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\TribCFOP;
use App\Entity\TribCST;
use App\Repository\TribCFOPRepository;
use App\Repository\TribCSTRepository;
use App\Service\Notify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/cst")
 */
class TribCSTController extends ControllerController
{
    /**
     * @Route("/", name="api_trib_cst_index", methods={"GET"})
     */
    public function index(Request $request, TribCSTRepository $CSTRepository, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($CSTRepository, $request, [], [], ['id', 'DESC'])),
            200,
            array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}", name="api_trib_cst_show", methods={"GET"})
     */
    public function show($id, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::single($id, TribCST::class, $notify)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}/edit", name="api_trib_cst_edit", methods={"POST"})
     * @throws \Exception
     */
    public function edit($id, ValidatorInterface $validator, Request $request, Notify $notify): Response
    {
        $conteudo = json_decode($request->getContent(), true);

        /**
         * @var $entityManager EntityManager
         */
        $entityManager = $this->getDoctrine()->getManager();

        if (!empty($id)) {
            $item = $this->getDoctrine()
                ->getRepository(TribCST::class)
                ->find($id);
            //$notify->addMessage($notify::TIPO_INFO, "Atualizando cadastro de Familia de produto");
        }
        else {
            $item = new TribCST();
            //$notify->addMessage($notify::TIPO_INFO, "Adicionando registro no cadastro de Familia de produto");
        }
        $item->setCodigo($conteudo["codigo"]);
        $item->setDescricao($conteudo["descricao"]);
        $item->setNome($conteudo["nome"]);
        $item->setTipo("E");

        $errors = $validator->validate($item);
        if (count($errors) > 0) {
            throw new \Exception($errors);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        $notify->addMessage($notify::TIPO_SUCCESS, "CST salvo com sucesso");
        return JsonResponse::fromJsonString(
            $notify->newReturn($item->getId()),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }
}