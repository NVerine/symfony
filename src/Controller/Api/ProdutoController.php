<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProdutoController extends AbstractController
{
    /**
     * @Route("/api/produto", name="api_produto_index", methods={"GET","HEAD"})
     */
    public function index()
    {
        echo "merda1";
    }

    /**
     * @Route("/api/produto/{id}", name="api_produto_show", methods={"GET","HEAD"}, defaults={"id": 0})
     */
    public function show(int $id)
    {
        echo "merda2";
    }

    /**
     * @Route("/api/produto/edit/{id}", name="api_produto_edit")
     */
    public function edit(int $id){
        echo "merda3";
    }
}
