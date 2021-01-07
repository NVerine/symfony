<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\Produto;
use App\Repository\ProdutoRepository;
use App\Service\Notify;
use NFePHP\NFe\Make;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/produto")
 */
class ProdutoController extends ControllerController
{
    /**
     * @Route("/", name="api_produto_index", methods={"GET"})
     */
    public function index(Request $request, ProdutoRepository $produtoRepository, Notify $notify): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($produtoRepository, $request)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}", name="api_produto_show", methods={"GET"})
     */
    public function show($id, Notify $notify): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::single($id, Produto::class, $notify)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }
}
