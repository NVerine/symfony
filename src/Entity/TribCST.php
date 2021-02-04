<?php

namespace App\Entity;

use App\Repository\TribCSTRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TribCSTRepository::class)
 */
class TribCST
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
    private $nome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descricao;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TribTipoOperacao", mappedBy="cst_origem")
     */
    private $tribTipoOperacaoOrigem;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TribTipoOperacao", mappedBy="cst_trib")
     */
    private $tribTipoOperacaoTrib;

    public function __construct()
    {
        $this->tribTipoOperacaoOrigem = new ArrayCollection();
        $this->tribTipoOperacaoTrib = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        if (!in_array($tipo, array('O', 'T'))) {    // saida, entrada
            throw new \Exception("Tipo de CST incorreto");
        }

        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return Collection|TribTipoOperacao[]
     */
    public function getTribTipoOperacaoTrib(): ?Collection
    {
        return $this->tribTipoOperacaoTrib;
    }

    public function addTribTipoOperacaoTrib(TribTipoOperacao $tribTipoOperacaoTrib): self
    {
        if (!$this->tribTipoOperacaoTrib->contains($tribTipoOperacaoTrib)) {
            $this->tribTipoOperacaoTrib[] = $tribTipoOperacaoTrib;
            $tribTipoOperacaoTrib->setCstTrib($this);
        }

        return $this;
    }

    public function removeTribTipoOperacaoTrib(TribTipoOperacao $tribTipoOperacaoTrib): self
    {
        if ($this->tribTipoOperacaoTrib->contains($tribTipoOperacaoTrib)) {
            $this->tribTipoOperacaoTrib->removeElement($tribTipoOperacaoTrib);
            // set the owning side to null (unless already changed)
            if ($tribTipoOperacaoTrib->getCstTrib() === $this) {
                $tribTipoOperacaoTrib->setCstTrib(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TribTipoOperacao[]
     */
    public function getTribTipoOperacaoOrigem(): ?Collection
    {
        return $this->tribTipoOperacaoOrigem;
    }

    public function addTribTipoOperacaoOrigem(TribTipoOperacao $tribTipoOperacaoOrigem): self
    {
        if (!$this->tribTipoOperacaoOrigem->contains($tribTipoOperacaoOrigem)) {
            $this->tribTipoOperacaoOrigem[] = $tribTipoOperacaoOrigem;
            $tribTipoOperacaoOrigem->setCstOrigem($this);
        }

        return $this;
    }

    public function removeTribTipoOperacaoOrigem(TribTipoOperacao $tribTipoOperacaoOrigem): self
    {
        if ($this->tribTipoOperacaoOrigem->contains($tribTipoOperacaoOrigem)) {
            $this->tribTipoOperacaoOrigem->removeElement($tribTipoOperacaoOrigem);
            // set the owning side to null (unless already changed)
            if ($tribTipoOperacaoOrigem->getCstOrigem() === $this) {
                $tribTipoOperacaoOrigem->setCstOrigem(null);
            }
        }

        return $this;
    }
}
