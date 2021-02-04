<?php


namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\FamiliaProduto;
use App\Entity\Produto;
use App\Repository\FamiliaProdutoRepository;
use App\Service\Notify;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/familia_produtos")
 */
class FamiliaProdutoController extends ControllerController
{
    /**
     * FamiliaProdutoController constructor.
     * @param FamiliaProdutoRepository $repository
     * @param Notify $notify
     */
    public function __construct(FamiliaProdutoRepository $repository, Notify $notify)
    {
        $this->entity = FamiliaProduto::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_familia_produto_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface|NonUniqueResultException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch($request, null, ['tb.codigo' => 'ASC'])));
    }

    /**
     * @Route("/list", name="_api_familia_produto_list", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface|NonUniqueResultException
     */
    public function list(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    /**
     * @Route("/{id}", name="api_familia_produto_show", methods={"GET"})
     * @throws Exception
     */
    public function show()
    {
        throw new Exception("Este método não deve ser implementado");
    }

    /**
     * @Route("/{id}/edit", name="api_familia_produto_edit", methods={"GET","POST"})
     * @param $id
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function edit($id, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $conteudo = json_decode($request->getContent(), true);

        foreach ($conteudo["itens"] as $r) {
            $this->getOrCreate($r["id"] ?? 0);

            /**
             * @var $item FamiliaProduto
             */
            $item = $this->createdEntity;
            $item->setCodigo($r["codigo"]);
            $item->setNome($r["nome"]);

            if (isset($r["exclui"]) && $r["exclui"]) {
                $filho = $this->em
                    ->getRepository($this->entity)
                    ->createQueryBuilder('a')
                    ->where('a.codigo LIKE :codigoLike')
                    ->andWhere('a.codigo <> :codigo')
                    ->setParameter('codigoLike', $r["codigo"] . "%")
                    ->setParameter('codigo', $r["codigo"])
                    ->getQuery()
                    ->getResult();

                if (count($filho) > 0) {
                    throw new Exception("Impossível deletar uma familia de produto que possui filhos");
                }

                $produtos = $this->em
                    ->getRepository(Produto::class)
                    ->findBy(['familia' => $r["id"]]);

                if (count($produtos) > 0) {
                    throw new Exception("Impossível deletar uma familia de produto que possui produtos vinculados");
                }

                $this->em->remove($item);
            } else {
                $this->em->persist($item);
            }
        }

        $this->em->flush();

        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "Familia de produto salva com sucesso");
        return $this->notifyReturn("");
    }
}