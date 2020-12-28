<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\Pessoa;
use App\Entity\PessoaContato;
use App\Entity\PessoaEndereco;
use App\Repository\PessoaRepository;
use App\Service\Notify;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/pessoa")
 */
class PessoaController extends ControllerController
{
    /**
     * @Route("/", name="api_pessoa_index", methods={"GET"})
     */
    public function index(Request $request, PessoaRepository $pessoaRepository, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($pessoaRepository, $request, [], array("endereco", "contato", "user"))),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/autocomplete/nome", name="_api_pessoa_autocomplete_nome", methods={"GET"})
     */
    public function autocompleto(Request $request){
        $conteudo = $request->query->all();

        $pessoa = $this->getDoctrine()
            ->getRepository(Pessoa::class)
            ->createQueryBuilder('a')
            ->where('a.nome LIKE :nome')
            ->setParameter('nome', $conteudo["pesq_nome"]."%")
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $temp = array();
        /**
         * @var $p Pessoa
         */
        foreach ($pessoa as $p){
            $arr = array();
            $arr["text"] = $p->getId()." | ".$p->getCpfCnpj()." | ".substr($p->getNome(), 0, 30);
            $arr["value"] = $p->getId();
            $arr["label"] = $p->getNome();
            $temp[] = $arr;
        }

        return JsonResponse::create(
            $temp,
            200,
            array('Symfony-Debug-Toolbar-Replace' => 1)
        );

    }

    /**
     * @Route("/{id}", name="api_pessoa_show", methods={"GET"})
     * @throws Exception
     */
    public function show($id, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::single($id, Pessoa::class, $notify)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}/edit", name="api_pessoa_edit", methods={"GET","POST"})
     */
    public function edit($id, ValidatorInterface $validator, Request $request, Notify $notify): Response
    {
        $conteudo = json_decode($request->getContent(), true);

        $entityManager = $this->getDoctrine()->getManager();

        if ($id > 0) {
            $pessoa = $this->getDoctrine()
                ->getRepository(Pessoa::class)
                ->find($id);
            $notify->addMessage($notify::TIPO_INFO, "Atualizando cadastro de Pessoas");
        } else {
            $pessoa = new Pessoa();
            $notify->addMessage($notify::TIPO_INFO, "Adicionando registro no cadastro de Pessoas");
        }

        //binarios
        @$pessoa->setAtivo($conteudo["ativo"] || true);
        @$pessoa->setCliente($conteudo["cliente"] || true);
        @$pessoa->setEmpresa($conteudo["empresa"] || true);
        @$pessoa->setFornecedor($conteudo["fornecedor"] || true);
        @$pessoa->setFuncionario($conteudo["funcionario"] || true);

        //demais
        $pessoa->setNome($conteudo["nome"]);
        $pessoa->setTipo($conteudo["tipo"]);
        $pessoa->setCpfCnpj($conteudo["cpfCnpj"]);
        if(!empty($conteudo["dataNascimento"])) {
            $data = \DateTime::createFromFormat('d-m-Y', $conteudo["dataNascimento"]);
            $pessoa->setDataNascimento($data);
        }
        $pessoa->setNomeFantasia($conteudo["nomeFantasia"]);
        @$pessoa->setRg($conteudo["rg"]);
        @$pessoa->setObservacoes($conteudo["observacoes"]);
        @$pessoa->setCnae($conteudo["cnae"]);

        $errors = $validator->validate($pessoa);
        if (count($errors) > 0) {
            throw new Exception($errors);
        }

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($pessoa);

        if(!empty($conteudo["contato"])) {
            foreach ($conteudo["contato"] as $c) {
                if (!empty($c["id"])) {
                    $contato = $this->getDoctrine()
                        ->getRepository(PessoaContato::class)
                        ->find($c["id"]);
                    $notify->addMessage($notify::TIPO_INFO, "Atualizando cadastro de contatos");
                } else {
                    $contato = new PessoaContato();
                    $notify->addMessage($notify::TIPO_INFO, "Adicionando registro no cadastro de contatos");
                }

                if (isset($c["exclui"]) && $c["exclui"]) {
                    // Now if we remove it, it will set the deletedAt field to the actual date
                    $entityManager->remove($contato);
                    $notify->addMessage($notify::TIPO_INFO, "Removendo registro do cadastro de contatos");
                } else {
                    $contato->setPessoa($pessoa);
                    $contato->setNome($c["nome"]);
                    $contato->setTelefone($c["telefone"]);
                    $contato->setEmail($c["email"]);
                    $entityManager->persist($contato);
                }
            }
        }

        if(!empty($conteudo["endereco"])) {
            foreach ($conteudo["endereco"] as $e) {
                if (!empty($e["id"])) {
                    $endereco = $this->getDoctrine()
                        ->getRepository(PessoaEndereco::class)
                        ->find($e["id"]);
                    $notify->addMessage($notify::TIPO_INFO, "Atualizando cadastro de endereços");
                } else {
                    $endereco = new PessoaEndereco();
                    $notify->addMessage($notify::TIPO_INFO, "Adicionando registro no cadastro de endereços");
                }

                if (isset($e["exclui"]) && $e["exclui"]) {
                    // Now if we remove it, it will set the deletedAt field to the actual date
                    $entityManager->remove($endereco);
                    $notify->addMessage($notify::TIPO_INFO, "Removendo registro do cadastro de endereços");
                } else {
                    $endereco->setPessoa($pessoa);
                    $endereco->setUf($e["uf"]);
                    $endereco->setCidade($e["cidade"]);
                    $endereco->setLogradouro($e["logradouro"]);
                    $endereco->setBairro($e["bairro"]);
                    $endereco->setComplemento($e["complemento"]);
                    $endereco->setNumero($e["numero"]);
                    $endereco->setCep(preg_replace("/[^0-9\.]/", "", $e["cep"]));
                    $endereco->setIbgeCidade($e["ibgeCidade"]);
//                    $endereco->setIbgeEstado($e["ibgeEstado"]);
                    $entityManager->persist($endereco);
                }
            }
        }

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $notify->addMessage($notify::TIPO_SUCCESS, "Pessoa salvo com sucesso");
        return JsonResponse::fromJsonString(
            $notify->newReturn($pessoa->getId()),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }
}
