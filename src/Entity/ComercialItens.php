<?php

namespace App\Entity;

use App\Repository\ComercialItensRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComercialItensRepository::class)
 */
class ComercialItens
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Comercial::class, inversedBy="comercialItens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comercial;

    /**
     * @ORM\ManyToOne(targetEntity=Produto::class, inversedBy="comercialItens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produto;

    /**
     * @ORM\ManyToOne(targetEntity=TribTipoOperacao::class, inversedBy="comercialItens")
     */
    private $operacao;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantidade;

    /**
     * @ORM\Column(type="float")
     */
    private $valor_unitario;

    /**
     * @ORM\Column(type="float")
     */
    private $valor_bruto;

    /**
     * @ORM\Column(type="float")
     */
    private $perc_desconto;

    /**
     * @ORM\Column(type="float")
     */
    private $valor_desconto;

    /**
     * @ORM\Column(type="float")
     */
    private $perc_ipi;

    /**
     * @ORM\Column(type="float")
     */
    private $valor_ipi;

    /**
     * @ORM\Column(type="float")
     */
    private $valor_total;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComercial(): ?Comercial
    {
        return $this->comercial;
    }

    public function setComercial(?Comercial $comercial): self
    {
        $this->comercial = $comercial;

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

    public function getOperacao(): ?TribTipoOperacao
    {
        return $this->operacao;
    }

    public function setOperacao(?TribTipoOperacao $operacao): self
    {
        $this->operacao = $operacao;

        return $this;
    }

    public function getQuantidade(): ?int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): self
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    public function getValorUnitario(): ?float
    {
        return $this->valor_unitario;
    }

    public function setValorUnitario(float $valor_unitario): self
    {
        $this->valor_unitario = $valor_unitario;

        return $this;
    }

    public function getValorBruto(): ?float
    {
        return $this->valor_bruto;
    }

    public function setValorBruto(float $valor_bruto): self
    {
        $this->valor_bruto = $valor_bruto;

        return $this;
    }

    public function getPercDesconto(): ?float
    {
        return $this->perc_desconto;
    }

    public function setPercDesconto(float $perc_desconto): self
    {
        $this->perc_desconto = $perc_desconto;

        return $this;
    }

    public function getValorDesconto(): ?float
    {
        return $this->valor_desconto;
    }

    public function setValorDesconto(float $valor_desconto): self
    {
        $this->valor_desconto = $valor_desconto;

        return $this;
    }

    public function getPercIpi(): ?float
    {
        return $this->perc_ipi;
    }

    public function setPercIpi(float $perc_ipi): self
    {
        $this->perc_ipi = $perc_ipi;

        return $this;
    }

    public function getValorIpi(): ?float
    {
        return $this->valor_ipi;
    }

    public function setValorIpi(float $valor_ipi): self
    {
        $this->valor_ipi = $valor_ipi;

        return $this;
    }

    public function getValorTotal(): ?float
    {
        return $this->valor_total;
    }

    public function setValorTotal(float $valor_total): self
    {
        $this->valor_total = $valor_total;

        return $this;
    }
}
