<?php

namespace App\Entity;

use App\Repository\TribCFOPRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TribCFOPRepository::class)
 */
class TribCFOP
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=350)
     */
    private $descricao;

    /**
     * @ORM\OneToMany(targetEntity=TribTipoOperacao::class, mappedBy="cfop")
     */
    private $tribTipoOperacaos;

    public function __construct()
    {
        $this->tribTipoOperacaos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        if(strlen($codigo) <> 4){
            throw new \Exception("O código do CFOP deve ter 4 dígitos");
        }
        $this->codigo = $codigo;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * @return Collection|TribTipoOperacao[]
     */
    public function getTribTipoOperacaos(): ?Collection
    {
        return $this->tribTipoOperacaos;
    }

    public function addTribTipoOperacao(TribTipoOperacao $tribTipoOperacao): self
    {
        if (!$this->tribTipoOperacaos->contains($tribTipoOperacao)) {
            $this->tribTipoOperacaos[] = $tribTipoOperacao;
            $tribTipoOperacao->setCfop($this);
        }

        return $this;
    }

    public function removeTribTipoOperacao(TribTipoOperacao $tribTipoOperacao): self
    {
        if ($this->tribTipoOperacaos->contains($tribTipoOperacao)) {
            $this->tribTipoOperacaos->removeElement($tribTipoOperacao);
            // set the owning side to null (unless already changed)
            if ($tribTipoOperacao->getCfop() === $this) {
                $tribTipoOperacao->setCfop(null);
            }
        }

        return $this;
    }
}
