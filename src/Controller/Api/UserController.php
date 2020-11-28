<?php


namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\GrupoUsuarios;
use App\Entity\Pessoa;
use App\Entity\PessoaContato;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Notify;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/user")
 */
class UserController extends ControllerController
{
    /**
     * @Route("/", name="api_user_index", methods={"GET"})
     */
    public function index(Request $request, UserRepository $userRepository, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($userRepository, $request, array("id", "username", "nomeGrupo"))),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}", name="api_user_show", methods={"GET"})
     */
    public function show($id, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::single($id, User::class, $notify)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}/edit", name="api_user_edit", methods={"GET","POST"})
     * @throws Exception
     */
    public function edit($id, Request $request, UserPasswordEncoderInterface $passwordEncoder, Notify $notify): Response
    {
        $data = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();

        if (($data["grupo"] == 1 && $id != 1) || ($id == 1 && $data["grupo"] != 1)) {
            throw new Exception("Apenas o usuário Admin deve e necessita permanecer no grupo superadmin");
        }

        $grupo  = $this->getDoctrine()
            ->getRepository(GrupoUsuarios::class)
            ->find($data["grupo"]);

        if($id > 0){
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($id);
        }
        else{
            $user = new User();

            if(!empty($data["nome"])){
                $pessoa = new Pessoa();
                $pessoa->setCpfCnpj($data["cpf"]);
                $pessoa->setTipo('F');
                $pessoa->setNome($data["nome"]);
                $pessoa->setNomeFantasia($data["nome"]);
                $entityManager->persist($pessoa);

                $contato = new PessoaContato();
                $contato->setNome("Usuário");
                $contato->setPessoa($pessoa);
                $contato->setEmail($data["email"]);
                $contato->setTelefone($data["telefone"]);
                $entityManager->persist($contato);
            }
        }

        // apenas para testes substituir quando tiver o autocomplete
        if(empty($pessoa)){
            $pessoa = $user->getPessoa();
        }

        $user->setPessoa($pessoa);

        if(isset($data["senha"])){
            if ($data["senha"] != $data["senha2"]) {
                throw new Exception("As senhas não conferem");
            }
            // atualiza a senha
            if(!empty($data["senha"])){
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

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $notify->addMessage($notify::TIPO_SUCCESS, "Usuario salvo com sucesso");
        return JsonResponse::fromJsonString(
            $notify->newReturn($user->getId()),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }
}