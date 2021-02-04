<?php

namespace App\Entity;

use App\Util\ValueHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PessoaRepository")
 */
class Pessoa
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Groups ({"pessoa_default"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"pessoa_default"})
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"pessoa_default"})
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"pessoa_default"})
     */
    private $nome_fantasia;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"pessoa_default"})
     */
    private $cpf_cnpj;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"pessoa_default"})
     */
    private $rg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups ({"pessoa_default"})
     */
    private $cnae;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups ({"pessoa_default"})
     */
    private $observacoes;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups ({"pessoa_default"})
     */
    private $data_nascimento;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"pessoa_default"})
     */
    private $ativo = true;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"pessoa_default"})
     */
    private $cliente = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"pessoa_default"})
     */
    private $fornecedor = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"pessoa_default"})
     */
    private $funcionario = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"pessoa_default"})
     */
    private $empresa = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PessoaEndereco", mappedBy="pessoa")
     * @Groups ({"pessoa_default"})
     */
    private $endereco;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PessoaContato", mappedBy="pessoa")
     * @Groups ({"pessoa_default"})
     */
    private $contato;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="pessoa")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Filial", mappedBy="pessoa")
     */
    private $filial;

    /**
     * @ORM\OneToOne(targetEntity=PessoaEndereco::class, cascade={"persist", "remove"})
     * @Groups ({"pessoa_default"})
     */
    private $enderecoPrincipal;

    /**
     * @ORM\OneToOne(targetEntity=PessoaContato::class, cascade={"persist", "remove"})
     * @Groups ({"pessoa_default"})
     */
    private $contatoPrincipal;

    /**
     * @ORM\OneToMany(targetEntity=Comercial::class, mappedBy="cliente")
     */
    private $comercials;

    public function __construct()
    {
        $this->endereco = new ArrayCollection();
        $this->contato = new ArrayCollection();
        $this->comercials = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @Groups ({"pessoa_index"})
     */
    public function getFullTipo(): string
    {
        if($this->tipo == "j") return "Jurídica";
        return "Física";
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getNomeFantasia(): ?string
    {
        return $this->nome_fantasia;
    }

    public function setNomeFantasia(string $nome_fantasia): self
    {
        $this->nome_fantasia = $nome_fantasia;

        return $this;
    }

    public function getCpfCnpj(): ?string
    {
        return $this->cpf_cnpj;
    }

    public function setCpfCnpj(string $cpf_cnpj): self
    {
        $this->cpf_cnpj = $cpf_cnpj;

        return $this;
    }

    public function getRg(): ?string
    {
        return $this->rg;
    }

    public function setRg(?string $rg): self
    {
        $this->rg = $rg;

        return $this;
    }

    public function getCnae(): ?int
    {
        return $this->cnae;
    }

    public function setCnae(?int $cnae): self
    {
        $this->cnae = $cnae;

        return $this;
    }

    public function getObservacoes(): ?string
    {
        return $this->observacoes;
    }

    public function setObservacoes(?string $observacoes): self
    {
        $this->observacoes = $observacoes;

        return $this;
    }

    public function getDataNascimento(): String
    {
        if(empty($this->data_nascimento)) return '';
        return date_format($this->data_nascimento, 'd-m-Y');
    }

    public function setDataNascimento(?\DateTimeInterface $data_nascimento): self
    {
        $this->data_nascimento = $data_nascimento;

        return $this;
    }

    public function getAtivo(): ?bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;

        return $this;
    }

    public function getCliente(): ?bool
    {
        return $this->cliente;
    }

    public function setCliente(bool $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getFornecedor(): ?bool
    {
        return $this->fornecedor;
    }

    public function setFornecedor(bool $fornecedor): self
    {
        $this->fornecedor = $fornecedor;

        return $this;
    }

    public function getFuncionario(): ?bool
    {
        return $this->funcionario;
    }

    public function setFuncionario(bool $funcionario): self
    {
        $this->funcionario = $funcionario;

        return $this;
    }

    public function getEmpresa(): ?bool
    {
        return $this->empresa;
    }

    public function setEmpresa(bool $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * @return Collection|PessoaEndereco[]
     */
    public function getEndereco(): ?Collection
    {
        return $this->endereco;
    }

    /**
     * @return string
     * @Groups({"pessoa_index"})
     */
    public function getEnderecoCompleto(): ?string
    {
        $endereco = $this->enderecoPrincipal;
        if(empty($endereco)) return null;
        $endereco_completo = "";
        /**
         * @var $endereco PessoaEndereco
         */

        if($endereco->getLogradouro()){
            $endereco_completo = $endereco->getLogradouro();
        }
        if($endereco->getNumero()){
            $endereco_completo .= " ".$endereco->getNumero();
        }
        if($endereco->getBairro()){
            $endereco_completo .= " - ".$endereco->getBairro();
        }
        if($endereco->getCidade()){
            $endereco_completo .= ", ".$endereco->getCidade();
        }
        if($endereco->getUf()){
            $endereco_completo .= "/".$endereco->getUf();
        }
        if($endereco->getCep()){
            $endereco_completo .= " ". ValueHelper::maskCep($endereco->getCep());
        }
        return $endereco_completo;
    }

    public function addEndereco(PessoaEndereco $endereco): self
    {
        if (!$this->endereco->contains($endereco)) {
            $this->endereco[] = $endereco;
            $endereco->setPessoa($this);
        }

        return $this;
    }

    public function removeEndereco(PessoaEndereco $endereco): self
    {
        if ($this->endereco->contains($endereco)) {
            $this->endereco->removeElement($endereco);
            // set the owning side to null (unless already changed)
            if ($endereco->getPessoa() === $this) {
                $endereco->setPessoa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PessoaContato[]
     */
    public function getContato(): ?Collection
    {
        return $this->contato;
    }

    public function addContato(PessoaContato $contato): self
    {
        if (!$this->contato->contains($contato)) {
            $this->contato[] = $contato;
            $contato->setPessoa($this);
        }

        return $this;
    }

    public function removeContato(PessoaContato $contato): self
    {
        if ($this->contato->contains($contato)) {
            $this->contato->removeElement($contato);
            // set the owning side to null (unless already changed)
            if ($contato->getPessoa() === $this) {
                $contato->setPessoa(null);
            }
        }

        return $this;
    }


    /**
     * @return string
     * @Groups({"pessoa_index"})
     */
    public function getContatoCompleto(): ?string
    {
        /**
         * @var $contato PessoaContato
         */
        $contato = $this->contatoPrincipal;
        if(empty($contato)) return null;

        $contato_completo = "";
        if($contato->getNome()){
            $contato_completo = $contato->getNome();
        }
        if($contato->getTelefone()){
            $contato_completo .= " ".$contato->getTelefone();
        }
        if($contato->getEmail()){
            $contato_completo .= " - ".$contato->getEmail();
        }
        return $contato_completo;
    }

    public function getUser(): ?Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setPessoa($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPessoa() === $this) {
                $user->setPessoa(null);
            }
        }

        return $this;
    }

    public function getFilial(): ?Filial
    {
        return $this->filial;
    }

    public function setFilial(Filial $filial): self
    {
        $this->filial = $filial;

        // set the owning side of the relation if necessary
        if ($filial->getPessoa() !== $this) {
            $filial->setPessoa($this);
        }

        return $this;
    }

    public function getEnderecoPrincipal(): ?PessoaEndereco
    {
        return $this->enderecoPrincipal;
    }

    public function setEnderecoPrincipal(?PessoaEndereco $enderecoPrincipal): self
    {
        $this->enderecoPrincipal = $enderecoPrincipal;

        return $this;
    }

    public function getContatoPrincipal(): ?PessoaContato
    {
        return $this->contatoPrincipal;
    }

    public function setContatoPrincipal(?PessoaContato $contatoPrincipal): self
    {
        $this->contatoPrincipal = $contatoPrincipal;

        return $this;
    }

    /**
     * @return Collection|Comercial[]
     */
    public function getComercials(): ?Collection
    {
        return $this->comercials;
    }

    public function addComercial(Comercial $comercial): self
    {
        if (!$this->comercials->contains($comercial)) {
            $this->comercials[] = $comercial;
            $comercial->setCliente($this);
        }

        return $this;
    }

    public function removeComercial(Comercial $comercial): self
    {
        if ($this->comercials->contains($comercial)) {
            $this->comercials->removeElement($comercial);
            // set the owning side to null (unless already changed)
            if ($comercial->getCliente() === $this) {
                $comercial->setCliente(null);
            }
        }

        return $this;
    }
}
