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
    /**
     * @Route("/", name="api_trib_cfop_index", methods={"GET"})
     */
    public function index(Request $request, TribCFOPRepository $CFOPRepository, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($CFOPRepository, $request, [], [], ['id', 'DESC'])),
            200,
            array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}", name="api_trib_cfop_show", methods={"GET"})
     */
    public function show($id, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::single($id, TribCFOP::class, $notify)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}/edit", name="api_trib_cfop_edit", methods={"POST"})
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

        $notify->addMessage($notify::TIPO_SUCCESS, "CFOP salvo com sucesso");
        return JsonResponse::fromJsonString(
            $notify->newReturn($item->getId()),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }
}