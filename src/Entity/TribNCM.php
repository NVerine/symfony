<?php

namespace App\Entity;

use App\Repository\TribNCMRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TribNCMRepository::class)
 */
class TribNCM
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=300)
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $codigo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $aliquota;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descricao;

    /**
     * @ORM\ManyToOne(targetEntity=Produto::class, inversedBy="ncm")
     */
    private $produto;

    /**
     * @ORM\ManyToOne(targetEntity=TribTipoOperacao::class, inversedBy="cst_trib")
     */
    private $tribTipoOperacaoTrib;

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

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getAliquota(): ?float
    {
        return $this->aliquota;
    }

    public function setAliquota(?float $aliquota): self
    {
        $this->aliquota = $aliquota;

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

    public function getProduto(): ?Produto
    {
        return $this->produto;
    }

    public function setProduto(?Produto $produto): self
    {
        $this->produto = $produto;

        return $this;
    }

    public function getTribTipoOperacaoTrib(): ?TribTipoOperacao
    {
        return $this->tribTipoOperacaoTrib;
    }

    public function setTribTipoOperacaoTrib(?TribTipoOperacao $tribTipoOperacaoTrib): self
    {
        $this->tribTipoOperacaoTrib = $tribTipoOperacaoTrib;

        return $this;
    }
}
