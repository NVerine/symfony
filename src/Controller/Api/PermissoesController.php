<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\GrupoUsuarios;
use App\Entity\Permissoes;
use App\Repository\GrupoUsuariosRepository;
use App\Service\Notify;
use App\Traits\Response;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/api/permissoes")
 */
class PermissoesController extends ControllerController
{
    use Response;

    /** @var string[]
     * rotas bloqueadas por exemplo, ainda em dev
     */
    public static $rotas_bloqueadas = [
            "api_comercial_index", "api_comercial_show", "api_comercial_edit", "api_discipline_index", "api_discipline_show",
            "api_produto_nfe", "api_nota_index", "api_produto_index", "api_produto_show", "api_produto_edit",
            "api_questions_index", "api_questions_list", "api_questions_show", "api_questions_edit", "api_test_index",
            "api_test_show", "api_test_edit", "api_trib_cfop_index", "api_trib_cfop_show", "api_trib_cfop_edit",
            "api_trib_cst_index", "api_trib_cst_show", "api_trib_cst_edit", "api_tipo_operacao_index", "api_tipo_operacao_show",
            "api_tipo_operacao_edit", "api_trib_ncm_index", "api_trib_ncm_show", "api_trib_ncm_edit"
        ];

    public static array $headers = [];
    /**
     * PermissoesController constructor.
     * @param GrupoUsuariosRepository $repository
     * @param Notify $notify
     */
    public function __construct(GrupoUsuariosRepository $repository, Notify $notify)
    {
        $this->entity = GrupoUsuarios::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_permissoes_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->response(
            $this->repository->fetch($request),
            self::$headers,
            ["grupousuario_default"]
        );
    }

    /**
     * @Route("/lista", name="_permissoes_lista")
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function listaPermissoes(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    /**
     * @Route("/current", name="_permissoes_current")
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function getCurrent(SessionInterface $session): JsonResponse
    {
        if ($this->getUser()->getGrupo()->getId() == 1) {
            return $this->listaRotas(true);
        }

        $permissoes = $session->get("permissoes");
        $retorno = array();
        /**
         * @var $p Permissoes
         */
        foreach ($permissoes as $p) {
            $retorno[$p->getRota()] = $p->getLiberado();
        }

        return $this->notifyReturn(json_encode($retorno));
    }

    /**
     * @Route("/rotas", name="_permissoes_lista_rotas")
     * @param null $value
     * @return JsonResponse
     */
    public function listaRotas($value = null): JsonResponse
    {
        return $this->notifyReturn(json_encode($this->montaRotas($value)));
    }

    /**
     * @Route("/{id}", name="api_permissoes_show", methods={"GET"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function show($id, Request $request): JsonResponse
    {
        return $this->response(
            $this->repository->fetch($request, $id)
        );

    }

    /**
     * @Route("/{id}/edit", name="api_permissoes_edit", methods={"GET","POST"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function edit($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();

        if ($id == 1) {
            throw new Exception("Não é possível editar o grupo do super usuario");
        }
        if ($id == 0) {
            $grupo = new GrupoUsuarios();
        } else {
            $grupo = $this->getDoctrine()
                ->getRepository(GrupoUsuarios::class)
                ->find($data["id"]);
        }

        $grupo->setNome($data["nome"]);
        $entityManager->persist($grupo);

        // salva as permissões em si -----------------------------------
        // primeiro exclui todas
        $permissoes = $this->getDoctrine()
            ->getRepository(Permissoes::class)
            ->findBy(array('grupo' => $grupo->getId()));

        foreach ($permissoes as $p) {
            $entityManager->remove($p);
        }

        // agora cria as novas
        foreach ($data["permissoes"] as $k => $v) {
            if ($v) {
                $permissao = new Permissoes();
                $permissao->setGrupo($grupo);
                $permissao->setLiberado(true);
                $permissao->setRota($k);
                $entityManager->persist($permissao);
            }
        }

        $entityManager->flush();

        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "Grupo salvo com sucesso");
        return $this->notifyReturn($grupo->getId());
    }

    /**
     * @param null $value
     * @return array
     */
    public function montaRotas($value = null)
    {
        $router = $this->get('router');
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();
        $routes = array();

        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params)
        {
            // se começa com _ então é rota obrigatória no sistema
            if(!in_array($route, self::$rotas_bloqueadas) && $route[0] != "_"){
                $routes[$route] = $value;//$params->getPath(); não preciso dos caminhos aqui
            }
        }
        return $routes;
    }
}