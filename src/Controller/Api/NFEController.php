<?php


namespace App\Controller\Api;

use NFePHP\NFe\Make;
use stdClass;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/nfe")
 */
class NFEController
{
    /**
     * @Route("/", name="api_produto_nfe")
     */
    public function nfe()
    {
        $nfe = new Make();

        $config = new stdClass();
        $config->versao = '4.00'; //versão do layout (string)
//        $config->Id = 'NFe35150271780456000160550010000000021800700082'; //se o Id de 44 digitos não for passado será gerado automaticamente
        $config->pk_nItem = null; //deixe essa variavel sempre como NULL
        $nfe->taginfNFe($config);

        $identificacao = new stdClass();
        $identificacao->cUF = 35;
        $identificacao->cNF = '80070008';
        $identificacao->natOp = 'VENDA';
        $identificacao->indPag = 0; //NÃO EXISTE MAIS NA VERSÃO 4.00
        $identificacao->mod = 55;
        $identificacao->serie = 1;
        $identificacao->nNF = 2;
        $identificacao->dhEmi = '2015-02-19T13:48:00-02:00';
        $identificacao->dhSaiEnt = null;
        $identificacao->tpNF = 1;
        $identificacao->idDest = 1;
        $identificacao->cMunFG = 3518800;
        $identificacao->tpImp = 1;
        $identificacao->tpEmis = 1;
        $identificacao->cDV = 2;
        $identificacao->tpAmb = 2;
        $identificacao->finNFe = 1;
        $identificacao->indFinal = 0;
        $identificacao->indPres = 0;
        $identificacao->procEmi = 0;
        $identificacao->verProc = '3.10.31';
        $identificacao->dhCont = null;
        $identificacao->xJust = null;
        $nfe->tagide($identificacao);

        dd($nfe);

        $emitente = new stdClass();
        $emitente->xNome;
        $emitente->xFant;
        $emitente->IE;
        $emitente->IEST;
        $emitente->IM;
        $emitente->CNAE;
        $emitente->CRT;
        $emitente->CNPJ; //indicar apenas um CNPJ ou CPF
        $emitente->CPF;
        $nfe->tagemit($emitente);

        $emitente_endereco = new stdClass();
        $emitente_endereco->xLgr;
        $emitente_endereco->nro;
        $emitente_endereco->xCpl;
        $emitente_endereco->xBairro;
        $emitente_endereco->cMun;
        $emitente_endereco->xMun;
        $emitente_endereco->UF;
        $emitente_endereco->CEP;
        $emitente_endereco->cPais;
        $emitente_endereco->xPais;
        $emitente_endereco->fone;
        $nfe->tagenderEmit($emitente_endereco);

        $destinatario = new stdClass();
        $destinatario->xNome;
        $destinatario->indIEDest;
        $destinatario->IE;
        $destinatario->ISUF;
        $destinatario->IM;
        $destinatario->email;
        $destinatario->CNPJ; //indicar apenas um CNPJ ou CPF ou idEstrangeiro
        $destinatario->CPF;
        $destinatario->idEstrangeiro;
        $nfe->tagdest($destinatario);

        $destinatario_endereco = new stdClass();
        $destinatario_endereco->xLgr;
        $destinatario_endereco->nro;
        $destinatario_endereco->xCpl;
        $destinatario_endereco->xBairro;
        $destinatario_endereco->cMun;
        $destinatario_endereco->xMun;
        $destinatario_endereco->UF;
        $destinatario_endereco->CEP;
        $destinatario_endereco->cPais;
        $destinatario_endereco->xPais;
        $destinatario_endereco->fone;
        $nfe->tagenderDest($destinatario_endereco);

        $produto = new stdClass();
        $produto->item = 1; //item da NFe
        $produto->cProd;
        $produto->cEAN;
        $produto->xProd;
        $produto->NCM;
        $produto->cBenef; //incluido no layout 4.00
        $produto->EXTIPI;
        $produto->CFOP;
        $produto->uCom;
        $produto->qCom;
        $produto->vUnCom;
        $produto->vProd;
        $produto->cEANTrib;
        $produto->uTrib;
        $produto->qTrib;
        $produto->vUnTrib;
        $produto->vFrete;
        $produto->vSeg;
        $produto->vDesc;
        $produto->vOutro;
        $produto->indTot;
        $produto->xPed;
        $produto->nItemPed;
        $produto->nFCI;
        $nfe->tagprod($produto);

        $produto_info_adicional = new stdClass();
        $produto_info_adicional->item = 1; //item da NFe
        $produto_info_adicional->infAdProd = 'informacao adicional do item';
        $nfe->taginfAdProd($produto_info_adicional);

        $total_tributos = new stdClass();
        $total_tributos->item = 1; //item da NFe
        $total_tributos->vTotTrib = 1000.00;
        $nfe->tagimposto($total_tributos);

        $icms = new stdClass();
        $icms->item = 1; //item da NFe
        $icms->orig;
        $icms->CST;
        $icms->modBC;
        $icms->vBC;
        $icms->pICMS;
        $icms->vICMS;
        $icms->pFCP;
        $icms->vFCP;
        $icms->vBCFCP;
        $icms->modBCST;
        $icms->pMVAST;
        $icms->pRedBCST;
        $icms->vBCST;
        $icms->pICMSST;
        $icms->vICMSST;
        $icms->vBCFCPST;
        $icms->pFCPST;
        $icms->vFCPST;
        $icms->vICMSDeson;
        $icms->motDesICMS;
        $icms->pRedBC;
        $icms->vICMSOp;
        $icms->pDif;
        $icms->vICMSDif;
        $icms->vBCSTRet;
        $icms->pST;
        $icms->vICMSSTRet;
        $icms->vBCFCPSTRet;
        $icms->pFCPSTRet;
        $icms->vFCPSTRet;
        $icms->pRedBCEfet;
        $icms->vBCEfet;
        $icms->pICMSEfet;
        $icms->vICMSEfet;
        $icms->vICMSSubstituto; //NT2018.005_1.10_Fevereiro de 2019
        $nfe->tagICMS($icms);

        $icms_partilha = new stdClass();
        $icms_partilha->item = 1; //item da NFe
        $icms_partilha->orig = 0;
        $icms_partilha->CST = '90';
        $icms_partilha->modBC = 0;
        $icms_partilha->vBC = 1000.00;
        $icms_partilha->pRedBC = null;
        $icms_partilha->pICMS = 18.00;
        $icms_partilha->vICMS = 180.00;
        $icms_partilha->modBCST = 1000.00;
        $icms_partilha->pMVAST = 40.00;
        $icms_partilha->pRedBCST = null;
        $icms_partilha->vBCST = 1400.00;
        $icms_partilha->pICMSST = 10.00;
        $icms_partilha->vICMSST = 140.00;
        $icms_partilha->pBCOp = 10.00;
        $icms_partilha->UFST = 'RJ';
        $nfe->tagICMSPart($icms_partilha);

        $ipi = new stdClass();
        $ipi->item = 1; //item da NFe
        $ipi->clEnq = null;
        $ipi->CNPJProd = null;
        $ipi->cSelo = null;
        $ipi->qSelo = null;
        $ipi->cEnq = '999';
        $ipi->CST = '50';
        $ipi->vIPI = 150.00;
        $ipi->vBC = 1000.00;
        $ipi->pIPI = 15.00;
        $ipi->qUnid = null;
        $ipi->vUnid = null;
        $nfe->tagIPI($ipi);

        $pis = new stdClass();
        $pis->item = 1; //item da NFe
        $pis->CST = '07';
        $pis->vBC = null;
        $pis->pPIS = null;
        $pis->vPIS = null;
        $pis->qBCProd = null;
        $pis->vAliqProd = null;
        $nfe->tagPIS($pis);

        $cofins = new stdClass();
        $cofins->item = 1; //item da NFe
        $cofins->CST = '07';
        $cofins->vBC = null;
        $cofins->pCOFINS = null;
        $cofins->vCOFINS = null;
        $cofins->qBCProd = null;
        $cofins->vAliqProd = null;
        $nfe->tagCOFINS($cofins);

        $issqn = new stdClass();
        $issqn->item = 1; //item da NFe
        $issqn->vBC = 1000.00;
        $issqn->vAliq = 5.00;
        $issqn->vISSQN = 50.00;
        $issqn->cMunFG = '3518800';
        $issqn->cListServ = '12.23';
        $issqn->vDeducao = null;
        $issqn->vOutro = null;
        $issqn->vDescIncond = null;
        $issqn->vDescCond = null;
        $issqn->vISSRet = null;
        $issqn->indISS = 2;
        $issqn->cServico = '123';
        $issqn->cMun = '3518800';
        $issqn->cPais = '1058';
        $issqn->nProcesso = null;
        $issqn->indIncentivo = 2;
        $nfe->tagISSQN($issqn);


        dd($nfe);
    }
}