<?php


namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\TribCFOP;
use App\Entity\TribCST;
use App\Entity\TribTipoOperacao;
use App\Repository\TribTipoOperacaoRepository;
use App\Service\Notify;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/tipooperacao")
 */
class TribTipoOperacaoController extends ControllerController
{
    public function __construct(TribTipoOperacaoRepository $repository, Notify $notify)
    {
        $this->entity = TribTipoOperacao::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_tipo_operacao_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface|NonUniqueResultException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->notifyReturn(
            parent::serialize(["items" =>$this->repository->fetch($request)])
        );
    }

    /**
     * @Route("/list", name="_api_tipo_operacao_list", methods={"GET"})
     */
    public function autocomplete(Request $request)
    {
        $operacao = $this->repository->fetch($request);
        $temp = array();
        /**
         * @var $p TribCST
         */
        foreach ($operacao as $p){
            $arr = array();
            $arr["text"] = $p->getCodigo().") ".substr($p->getNome(), 0, 40);
            $arr["value"] = $p->getId();
            $arr["label"] = $p->getNome();
            $temp[] = $arr;
        }

        return $this->notifyReturn(json_encode($temp));
    }

    /**
     * @Route("/{id}", name="api_tipo_operacao_show", methods={"GET"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ExceptionInterface
     */
    public function show(Request $request, $id): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch($request, $id)));
    }

    /**
     * @Route("/{id}/edit", name="api_tipo_operacao_edit", methods={"POST"})
     * @param $id
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function edit($id, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $conteudo = json_decode($request->getContent(), true);
        $this->getOrCreate($id);

        $cfop = $this->getDoctrine()
            ->getRepository(TribCFOP::class)
            ->find($conteudo["cfop"]["id"]);

        $origem = $this->getDoctrine()
            ->getRepository(TribCST::class)
            ->find($conteudo["cstOrigem"]["id"]);

        $trib = $this->getDoctrine()
            ->getRepository(TribCST::class)
            ->find($conteudo["cstTrib"]["id"]);

        /**
         * @var $item TribTipoOperacao
         */
        $item = $this->createdEntity;
        $item->setNome($conteudo["nome"]);
        $item->setCodigo($conteudo["codigo"]);
        $item->setDescricao($conteudo["descricao"]);
        $item->setTipo($conteudo["tipo"]);
        $item->setCsosn($conteudo["csosn"]);
        $item->setIcmstipo($conteudo["icmstipo"]);
        $item->setIcmsbase($conteudo["icmsbase"]);
        $item->setPisaliquota($conteudo["pisaliquota"]);
        $item->setCofinsaliquota($conteudo["cofinsaliquota"]);
        $item->setIssqnaliquota($conteudo["issqnaliquota"]);
        $item->setCfop($cfop);
        $item->setCstOrigem($origem);
        $item->setCstTrib($trib);
//        $item->setPreco(ValueHelper::moneyToFloat($conteudo["preco"]));
//        $item->setDescription($conteudo["description"]);
//        $item->setFamilia($familia);

        return $this->insertOrUpdate($validator, "Tipo Operação");
    }

}