<?php


namespace App\Controller\Api;


use App\Controller\ControllerController;
use App\Entity\GrupoUsuarios;
use App\Entity\Permissoes;
use App\Repository\GrupoUsuariosRepository;
use App\Repository\PermissoesRepository;
use App\Service\Notify;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/permissoes")
 */
class PermissoesController extends ControllerController
{
    public function __construct(GrupoUsuariosRepository $repository, Notify $notify)
    {
        $this->entity = GrupoUsuarios::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_permissoes_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->notifyReturn(parent::lista($request, [], ["id", "nome"], []));
    }

    /**
     * @Route("/lista", name="_permissoes_lista")
     */
    public function listaPermissoes(Request $request) :Response
    {
        return $this->index($request);
    }

    /**
     * @Route("/current", name="_permissoes_current")
     */
    public function getCurrent(SessionInterface $session): Response
    {
        if($this->getUser()->getGrupo()->getId() == 1){
            return $this->listaRotas(true);
        }

        $permissoes = $session->get("permissoes");
        $retorno = array();
        /**
         * @var $p Permissoes
         */
        foreach ($permissoes as $p){
            $retorno[$p->getRota()] = $p->getLiberado();
        }

        return $this->notifyReturn(json_encode($retorno));
    }

    /**
     * @Route("/rotas", name="_permissoes_lista_rotas")
     */
    public function listaRotas($value = null): Response
    {
        $router = $this->get('router');
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();
        $routes = array();

        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params)
        {
            // se começa com _ então é rota obrigatória no sistema
            if($route[0] != "_"){
                $routes[$route] = $value;//$params->getPath(); não preciso dos caminhos aqui
            }
        }
        return $this->notifyReturn(json_encode($routes));
    }

    /**
     * @Route("/{id}", name="api_permissoes_show", methods={"GET"})
     */
    public function show($id): Response
    {
        return $this->notifyReturn(parent::single($id));
    }

    /**
     * @Route("/{id}/edit", name="api_permissoes_edit", methods={"GET","POST"})
     */
    public function edit($id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();

        if($id == 0){
            $grupo = new GrupoUsuarios();
        }
        else{
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

        foreach ($permissoes as $p){
            $entityManager->remove($p);
        }

        // agora cria as novas
        foreach ($data["permissoes"] as $k => $v){
            if($v){
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
}