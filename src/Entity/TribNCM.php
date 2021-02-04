<?php

namespace App\Entity;

use App\Repository\TribNCMRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TribNCMRepository::class)
 */
class TribNCM
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=300)
     * @Groups({"default"})
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $codigo;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"default"})
     */
    private $aliquota;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"default"})
     */
    private $descricao;

    /**
     * @ORM\ManyToOne(targetEntity=Produto::class, inversedBy="ncm")
     */
    private $produto;

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

    /**
     * @Groups({"default"})
     */
    public function getCodigo(): ?string
    {
        return substr($this->codigo, 0, 4). "." .
            substr($this->codigo, 4, 2). "." .
            substr($this->codigo, 6, 2);
    }

    public function setCodigo(?int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getRawCodigo(): ?string
    {
        return $this->codigo;
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
}
