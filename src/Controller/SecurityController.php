<?php

namespace App\Controller;

use App\Entity\GrupoUsuarios;
use App\Entity\Pessoa;
use App\Entity\User;
use App\Service\Notify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="_app_login", methods={"POST"})
     * @throws \Exception
     */
    public function login(Notify $notify, UserPasswordEncoderInterface $encoder, Request $request)
    {
        $this->popula($encoder);

        // salva momento e token do login para verificação
        $random = bin2hex(random_bytes(60));

        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());

        $usuario = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(array("username" => $request->request->get("username")));

        if(empty($usuario)){
            throw new \Exception("Username não encontrado");
        }

        /**
         * @var $usuario User
         */
        $usuario = $usuario[0];
        if(!$encoder->isPasswordValid($usuario, $request->request->get("password"))){
            throw new \Exception("Os dados não conferem");
        }

        $date = new \DateTime();
        $usuario->setLoginDate($date);
        $usuario->setApiToken($random);

        $em = $this->getDoctrine()->getManager();
        $em->persist($usuario);
        $em->flush();

        $retorno["token"] = $random;
        $retorno["date"] = $date;

        return JsonResponse::fromJsonString(
            $notify->newReturn(json_encode($retorno)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    private function popula(UserPasswordEncoderInterface $encoder){
        // 1) Procura todos os usuarios
        $usuario = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        // 2) Senão tem nenhum, cadastra um novo
        // não atrelar outras funções de login dentro desta condicional
        if (empty($usuario)) {
            // 3) cadastra grupo primeiro
            $em = $this->getDoctrine()->getManager();

            $grupo = new GrupoUsuarios();
            $grupo->setNome("Super Administrador");

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($grupo);

            // cria uma pessoa antes
            $pessoa = new Pessoa();
            $pessoa->setNome("Administrador");
            $pessoa->setTipo("F");
            $pessoa->setNomeFantasia("Admin");
            $pessoa->setCpfCnpj("000.000.000-00");

            $em->persist($pessoa);
            // TODO salvar pessoa endereço e pessoa contato aqui

            $usuario = new User();
            // faz o encode da senha
            $encoded = $encoder->encodePassword($usuario, "admin");
            $usuario->setPassword($encoded);
            $usuario->setPessoa($pessoa);
            $usuario->setUsername("Administrador");
            $usuario->setUsername("admin");
            $usuario->setGrupo($grupo);  // grupo superadmin

            // 3) salva o usuario
            $em = $this->getDoctrine()->getManager();

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($usuario);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
        }
    }
}