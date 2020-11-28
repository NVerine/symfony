<?php


namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\FamiliaProduto;
use App\Repository\FamiliaProdutoRepository;
use App\Service\Notify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/familia_produtos")
 */
class FamiliaProdutoController extends ControllerController
{
    /**
     * @Route("/", name="api_familia_produto_index", methods={"GET"})
     */
    public function index(Request $request, FamiliaProdutoRepository $familiaProduto, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($familiaProduto, $request, [], [], ['codigo', 'ASC'])),
            200,
            array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/list", name="_api_familia_produto_list", methods={"GET"})
     */
    public function list(Request $request, FamiliaProdutoRepository $familiaProduto, Notify $notify): Response
    {
        return $this->index($request, $familiaProduto, $notify);
    }

    /**
     * @Route("/{id}", name="api_familia_produto_show", methods={"GET"})
     * @throws \Exception
     */
    public function show(){
        throw new \Exception("Este método não deve ser implementado");
    }

    /**
     * @Route("/{id}/edit", name="api_familia_produto_edit", methods={"GET","POST"})
     * @throws \Exception
     */
    public function edit($id, ValidatorInterface $validator, Request $request, Notify $notify): Response
    {
        $conteudo = json_decode($request->getContent(), true);

        /**
         * @var $entityManager EntityManager
         */
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($conteudo["itens"] as $r){
            if (!empty($r["id"])) {
                $item = $this->getDoctrine()
                    ->getRepository(FamiliaProduto::class)
                    ->find($r["id"]);
                //$notify->addMessage($notify::TIPO_INFO, "Atualizando cadastro de Familia de produto");
            }
            else {
                $item = new FamiliaProduto();
                //$notify->addMessage($notify::TIPO_INFO, "Adicionando registro no cadastro de Familia de produto");
            }
            $item->setCodigo($r["codigo"]);
            $item->setNome($r["nome"]);

            $errors = $validator->validate($item);
            if (count($errors) > 0) {
                throw new \Exception($errors);
            }

            if (isset($r["exclui"]) && $r["exclui"]) {
                $filho = $entityManager
                    ->getRepository(FamiliaProduto::class)
                    ->createQueryBuilder('a')
                    ->where('a.codigo LIKE :codigoLike')
                    ->andWhere('a.codigo <> :codigo')
                    ->setParameter('codigoLike', $r["codigo"]."%")
                    ->setParameter('codigo', $r["codigo"])
                    ->getQuery()
                    ->getResult();

                if(count($filho) > 0){
                    throw new \Exception("Impossível deletar uma familia de produto que possui filhos");
                }
                // TODO colocar verificação se possui produto nesta familia
                // TODO limitar os níveis para 4
                $entityManager->remove($item);
            }
            else{
                $entityManager->persist($item);
            }
        }
        $entityManager->flush();

        $notify->addMessage($notify::TIPO_SUCCESS, "Familia de produto salva com sucesso");
        return JsonResponse::fromJsonString(
            $notify->newReturn(""),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }
}