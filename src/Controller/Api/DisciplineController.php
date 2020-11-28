<?php


namespace App\Controller\Api;


use App\Controller\ControllerController;
use App\Entity\Disciplina;
use App\Repository\DisciplinaRepository;
use App\Service\Notify;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/discipline")
 */
class DisciplineController extends ControllerController
{
    /**
     * @Route("/", name="api_discipline_index", methods={"GET"})
     */
    public function index(Request $request, DisciplinaRepository $disciplinaRepository, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($disciplinaRepository, $request)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}", name="api_discipline_show", methods={"GET"})
     * @throws Exception
     */
    public function show($id, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::single($id, Disciplina::class, $notify)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }
}