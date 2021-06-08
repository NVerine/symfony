<?php


namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\Filial;
use App\Entity\GrupoUsuarios;
use App\Entity\Pessoa;
use App\Entity\PessoaContato;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Notify;
use App\Traits\Response;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/api/user")
 */
class UserController extends ControllerController
{
    use Response;

    public static array $headers = [];

    /**
     * UserController constructor.
     * @param UserRepository $repository
     * @param Notify $notify
     */
    public function __construct(UserRepository $repository, Notify $notify)
    {
        $this->entity = User::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_user_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->response(
            $this->repository->fetch($request),
            self::$headers,
            ["user_default"]
        );
    }

    /**
     * @Route("/{id}", name="api_user_show", methods={"GET"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException|ExceptionInterface
     */
    public function show($id, Request $request): JsonResponse
    {
        return $this->notifyReturn(
            parent::serialize(
                [
                    "items" => $this->repository->fetch($request, $id)
                ], [], [],
                ["password"]
            )
        );
    }

    /**
     * @Route("/{id}/edit", name="api_user_edit", methods={"GET","POST"})
     * @throws Exception
     */
    public function edit($id, Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();

        dump($data);
        if (($data["grupo"] == 1 && $id != 1) || ($id == 1 && $data["grupo"] != 1)) {
            throw new Exception("Apenas o usuário Admin deve e necessita permanecer no grupo superadmin");
        }

        $grupo = $this->getDoctrine()
            ->getRepository(GrupoUsuarios::class)
            ->find($data["grupo"]);

        if ($id > 0) {
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($id);
        } else {
            $user = new User();

            $username_existe = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(['username' => $data["username"]]);

            if (!empty($username_existe)) {
                throw new Exception("Este Username já está em uso");
            }

            if (empty($data["senha"]) || empty($data["senha2"])) {
                throw new Exception("É necessário informar uma senha para o cadastro.");
            }

            if (!$data["pessoa_existe"]) {
                $pessoa = new Pessoa();
                $pessoa->setCpfCnpj($data["pessoa"]["cpf"]);
                $pessoa->setTipo('F');
                $pessoa->setNome($data["pessoa"]["nome"]);
                $pessoa->setNomeFantasia($data["pessoa"]["nome"]);
                $entityManager->persist($pessoa);

                $contato = new PessoaContato();
                $contato->setNome("Usuário");
                $contato->setPessoa($pessoa);
                $contato->setEmail($data["pessoa"]["email"]);
                $contato->setTelefone($data["pessoa"]["telefone"]);
                $entityManager->persist($contato);
            }
        }

        if (empty($pessoa)) {
            $pessoa = $this->getDoctrine()
                ->getRepository(Pessoa::class)
                ->find($data["pessoa"]["id"]);
        }

        $user->setPessoa($pessoa);

        if (isset($data["senha"])) {
            if ($data["senha"] != $data["senha2"]) {
                throw new Exception("As senhas não conferem");
            }
            // atualiza a senha
            if (!empty($data["senha"])) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $data["senha"]
                    )
                );
            }
        }

        $user->setUsername($data["username"]);
        $user->setGrupo($grupo);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // salva os vinculos com filial
        if (!empty($data["filiais"])) {
            foreach ($data["filiais"] as $f) {
                //primeiro exclui
                $filial_vinculada = $this->getDoctrine()
                    ->getRepository(Filial::class)
                    ->find($f["id"]);

                if (isset($f["exclui"]) && $f["exclui"]) {
                    $user->removeFiliais($filial_vinculada);
                    $filial_vinculada->removeUser($user);
                } else {
                    $user->addFilais($filial_vinculada);
                    $filial_vinculada->addUser($user);
                }
                $entityManager->persist($user);
            }
        }

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "Usuario salvo com sucesso");
        return $this->notifyReturn($user->getId());
    }

    /**
     * @Route("/perfil/{id}/edit", name="api_perfil_edit", methods={"GET","POST"})
     * @throws Exception
     */
    public function perfil_edit($id, Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();

        if (!($id > 0)) {
            throw new Exception("Impossível realizar cadastro aqui.");
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (isset($data["senha"])) {
            if ($data["senha"] != $data["senha2"]) {
                throw new Exception("As senhas não conferem");
            }
            // atualiza a senha
            if (!empty($data["senha"])) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $data["senha"]
                    )
                );
            }
        }

        $filial_ativa = $this->getDoctrine()->getRepository(Filial::class)->find($data["filialAtiva"]["id"]);
        $user->setFilialAtiva($filial_ativa);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);


        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "Usuario salvo com sucesso");
        return $this->notifyReturn($user->getId());
    }
}