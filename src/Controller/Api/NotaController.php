<?php


namespace App\Controller\Api;



use App\Controller\ControllerController;
use App\Service\Notify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use NFePHP\NFe\Make;

/**
 * @Route("/api/nota")
 */
class NotaController extends ControllerController
{
    /**
     * @Route("/", name="api_nota_index", methods={"GET"})
     */
    public function index(Request $request, Notify $notify): Response
    {
        $nfe = new Make();

        $std = new \stdClass();
        $std->cUF = 35;
        $std->cNF = '80070008';
        $std->natOp = 'VENDA';
        $std->versao = '4.00'; //versão do layout (string)

        $std->mod = 55;
        $std->serie = 1;
        $std->nNF = 2;
        $std->dhEmi = '2015-02-19T13:48:00-02:00';
        $std->dhSaiEnt = null;
        $std->tpNF = 1;
        $std->idDest = 1;
        $std->cMunFG = 3518800;
        $std->tpImp = 1;
        $std->tpEmis = 1;
        $std->cDV = 2;
        $std->tpAmb = 2;
        $std->finNFe = 1;
        $std->indFinal = 0;
        $std->indPres = 0;
        $std->procEmi = 0;
        $std->verProc = '3.10.31';
        $std->dhCont = null;
        $std->xJust = null;

        $nfe->tagide($std);

        print_r($nfe);

//        return JsonResponse::fromJsonString(
//            $notify->newReturn(parent::lista($familiaProduto, $request, [], [], ['codigo', 'ASC'])),
//            200,
//            array('Symfony-Debug-Toolbar-Replace' => 1)
//        );
    }
}