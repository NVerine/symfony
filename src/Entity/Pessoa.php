<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PessoaRepository")
 */
class Pessoa
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome_fantasia;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cpf_cnpj;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cnae;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacoes;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $data_nascimento;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ativo = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cliente = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fornecedor = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $funcionario = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $empresa = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PessoaEndereco", mappedBy="pessoa")
     */
    private $endereco;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PessoaContato", mappedBy="pessoa")
     */
    private $contato;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="pessoa", cascade={"persist", "remove"})
     */
    private $user;

    public function __construct()
    {
        $this->endereco = new ArrayCollection();
        $this->contato = new ArrayCollection();
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
    public function getEndereco(): Collection
    {
        return $this->endereco;
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
    public function getContato(): Collection
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        // set the owning side of the relation if necessary
        if ($user->getPessoa() !== $this) {
            $user->setPessoa($this);
        }

        return $this;
    }
}
