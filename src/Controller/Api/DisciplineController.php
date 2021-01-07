<?php


namespace App\Controller\Api;


use App\Controller\ControllerController;
use App\Entity\Disciplina;
use App\Repository\DisciplinaRepository;
use App\Service\Notify;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/discipline")
 */
class DisciplineController extends ControllerController
{
    public function __construct(DisciplinaRepository $repository, Notify $notify)
    {
        $this->entity = Disciplina::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_discipline_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->notifyReturn(parent::lista($request));
    }

    /**
     * @Route("/{id}", name="api_discipline_show", methods={"GET"})
     * @throws Exception
     */
    public function show($id): Response
    {
        return $this->notifyReturn(parent::single($id));
    }
}