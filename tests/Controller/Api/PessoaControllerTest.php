<?php

namespace App\Tests\Controller\Api;

use App\Controller\Api\PessoaController;
use App\Entity\Pessoa;
use App\Tests\NverineTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PessoaControllerTest
 * @package App\Tests\Controller\Api
 */
class PessoaControllerTest extends NverineTestCase
{
    public string $entity = Pessoa::class;

    protected function setUp()
    {
        parent::setUp();
    }

    public function testSuperADM()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $controller = new PessoaController($this->repository, $this->notify);

        /**
         * @var $retorno JsonResponse
         */
        $retorno = $controller->show( Pessoa::super_adm, $request);
        $retorno = json_decode($retorno->getContent(), true);

        $this->assertEquals("Super Administrador", $retorno["dados"]["items"]["nome"], "O nome do super admin foi alterado");
        $this->assertEquals(1, $retorno["dados"]["items"]["ativo"], "O super admin não está ativo");
    }

    public function testIndex()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $controller = new PessoaController($this->repository, $this->notify);

        /**
         * @var $retorno JsonResponse
         */
        $retorno = $controller->index($request);
        $retorno = json_decode($retorno->getContent(), true);
        $this->assertNotEmpty($retorno["dados"]["items"], "Nenhum item encontrado");
    }

    public function testPag10()
    {
        $request = new Request( ["pesq_limite" => 10, "pesq_offset" => 0]);
        $request->setMethod(Request::METHOD_GET);
        $controller = new PessoaController($this->repository, $this->notify);

        /**
         * @var $retorno JsonResponse
         */
        $retorno = $controller->index($request);
        $retorno = json_decode($retorno->getContent(), true);
        $this->assertCount(10,$retorno["dados"]["items"], "A paginação parece quebrada ou não foram executados os fixtures");
    }

    public function testeAutocomplete()
    {
        $request = new Request(["pesq_nome" => "Super Administrador"]);
        $request->setMethod(Request::METHOD_GET);
        $controller = new PessoaController($this->repository, $this->notify);

        /**
         * @var $retorno JsonResponse
         */
        $retorno = $controller->autocompleto( $request);
        $retorno = json_decode($retorno->getContent(), true);

        $this->assertEquals(1, $retorno[0]["value"], "Autocomplete com problema");
    }

    public function testeEdit()
    {
        $content = json_encode(["nome" => "Testador"]);
        $request = new Request([],[],[],[],[],[], $content);
        $request->setMethod(Request::METHOD_POST);
        $controller = new PessoaController($this->repository, $this->notify);
        $validator = static::$kernel->getContainer()->get('validator');
        $manager = static::$kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        /**
         * @var $retorno JsonResponse
         */
        $retorno = $controller->edit( 0, $validator, $request, $manager);
        $retorno = json_decode($retorno->getContent(), true);

        print_r($retorno);

//        $this->assertEquals(1, $retorno[0]["value"], "Autocomplete com problema");
    }
}