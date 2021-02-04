<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\Pessoa;
use App\Entity\PessoaContato;
use App\Entity\PessoaEndereco;
use App\Repository\PessoaRepository;
use App\Service\Notify;
use App\Util\ValueHelper;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/pessoa")
 */
class PessoaController extends ControllerController
{
    /**
     * atentar para ordenação
     * @var array
     */
    public static array $headers = [
        "id",
        "nome",
        ["fullTipo" => "tipo"],
        "nome_fantasia",
        "cpf_cnpj",
        "rg",
        "cnae",
        "data_nascimento",
        "ativo",
        "cliente",
        "fornecedor",
        "funcionario",
        "empresa",
        ["enderecoCompleto" => "Endereço"],
        ["contatoCompleto" => "contato"]
    ];

    /**
     * PessoaController constructor.
     * @param PessoaRepository $repository
     * @param Notify $notify
     */
    public function __construct(PessoaRepository $repository, Notify $notify)
    {
        $this->entity = Pessoa::class;
        $this->repository = $repository;
        $this->notify = $notify;
    }

    /**
     * @Route("/", name="api_pessoa_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->notifyReturn(
            parent::serialize(
                ["headers" => self::$headers, "items" => $this->repository->fetch($request)],
                ["pessoa_default", "pessoaendereco_default", "pessoacontato_default", "pessoa_index"]
            )
        );
    }

    /**
     * @Route("/autocomplete/nome", name="_api_pessoa_autocomplete_nome", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function autocompleto(Request $request): JsonResponse
    {
        $conteudo = $request->query->all();

        $pessoa = $this->getDoctrine()
            ->getRepository(Pessoa::class)
            ->createQueryBuilder('a')
            ->where('a.nome LIKE :nome')
            ->setParameter('nome', $conteudo["pesq_nome"] . "%")
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $temp = array();
        /**
         * @var $p Pessoa
         */
        foreach ($pessoa as $p) {
            $arr = array();
            $arr["text"] = $p->getId() . " | " . $p->getCpfCnpj() . " | " . substr($p->getNome(), 0, 30);
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
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ExceptionInterface
     */
    public function show($id, Request $request): JsonResponse
    {
        return $this->notifyReturn(parent::serialize($this->repository->fetch($request, $id), [], [], ['user', 'filial', 'comercials']));
    }

    /**
     * @Route("/{id}/edit", name="api_pessoa_edit", methods={"GET","POST"})
     * @param $id
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function edit($id, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $conteudo = json_decode($request->getContent(), true);

        $entityManager = $this->getDoctrine()->getManager();

        if ($id > 0) {
            $pessoa = $this->getDoctrine()
                ->getRepository(Pessoa::class)
                ->find($id);
//            $notify->addMessage($notify::TIPO_INFO, "Atualizando cadastro de Pessoas");
        } else {
            $pessoa = new Pessoa();
//            $notify->addMessage($notify::TIPO_INFO, "Adicionando registro no cadastro de Pessoas");
        }

        //binarios
        @$pessoa->setAtivo(ValueHelper::toBinary($conteudo["ativo"]));
        @$pessoa->setCliente(ValueHelper::toBinary($conteudo["cliente"]));
        @$pessoa->setEmpresa(ValueHelper::toBinary($conteudo["empresa"]));
        @$pessoa->setFornecedor(ValueHelper::toBinary($conteudo["fornecedor"]));
        @$pessoa->setFuncionario(ValueHelper::toBinary($conteudo["funcionario"]));

        //demais
        $pessoa->setNome($conteudo["nome"]);
        $pessoa->setTipo($conteudo["tipo"]);
        $pessoa->setCpfCnpj($conteudo["cpfCnpj"]);
        if (!empty($conteudo["dataNascimento"])) {
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

        if (!empty($conteudo["contato"])) {
            foreach ($conteudo["contato"] as $k => $c) {
                if (!empty($c["id"])) {
                    $contato = $this->getDoctrine()
                        ->getRepository(PessoaContato::class)
                        ->find($c["id"]);
//                    $notify->addMessage($notify::TIPO_INFO, "Atualizando cadastro de contatos");
                } else {
                    $contato = new PessoaContato();
//                    $notify->addMessage($notify::TIPO_INFO, "Adicionando registro no cadastro de contatos");
                }

                if (isset($c["exclui"]) && $c["exclui"]) {
                    // Now if we remove it, it will set the deletedAt field to the actual date
                    $entityManager->remove($contato);
                    $this->notify->addMessage($this->notify::TIPO_INFO, "Removendo registro do cadastro de contatos");
                } else {
                    $contato->setPessoa($pessoa);
                    $contato->setNome($c["nome"]);
                    $contato->setTelefone($c["telefone"]);
                    $contato->setEmail($c["email"]);
                    $entityManager->persist($contato);
                    if ($conteudo["contatoPrincipal"] == $k) {
                        $pessoa->setContatoPrincipal($contato);
                    }
                }
            }
        }

        if (!empty($conteudo["endereco"])) {
            foreach ($conteudo["endereco"] as $k => $e) {
                if (!empty($e["id"])) {
                    $endereco = $this->getDoctrine()
                        ->getRepository(PessoaEndereco::class)
                        ->find($e["id"]);
//                    $notify->addMessage($notify::TIPO_INFO, "Atualizando cadastro de endereços");
                } else {
                    $endereco = new PessoaEndereco();
//                    $notify->addMessage($notify::TIPO_INFO, "Adicionando registro no cadastro de endereços");
                }

                if (isset($e["exclui"]) && $e["exclui"]) {
                    // Now if we remove it, it will set the deletedAt field to the actual date
                    $entityManager->remove($endereco);
                    $this->notify->addMessage($this->notify::TIPO_INFO, "Removendo registro do cadastro de endereços");
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
                    if ($conteudo["enderecoPrincipal"] == $k) {
                        $pessoa->setEnderecoPrincipal($endereco);
                    }
                }
            }
        }

        // atualiza contato e endereço principal
        $entityManager->persist($pessoa);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "Pessoa salvo com sucesso");
        return $this->notifyReturn($pessoa->getId());
    }
}
