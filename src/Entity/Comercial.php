<?php

namespace App\Entity;

use App\Repository\ComercialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComercialRepository::class)
 */
class Comercial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Pessoa::class, inversedBy="comercials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tipo;

    /**
     * @ORM\Column(type="integer")
     */
    private $modelo;

    /**
     * @ORM\Column(type="integer")
     */
    private $finalidade;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data_emissao;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $num_nf;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $natureza;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $info_fisco;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nf_referencia;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $info_complementar;

    /**
     * @ORM\OneToMany(targetEntity=ComercialItens::class, mappedBy="comercial")
     */
    private $comercialItens;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data_lancamento;

    public function __construct()
    {
        $this->comercialItens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCliente(): ?Pessoa
    {
        return $this->cliente;
    }

    public function setCliente(?Pessoa $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
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

    public function getModelo(): ?int
    {
        return $this->modelo;
    }

    public function setModelo(int $modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getFinalidade(): ?int
    {
        return $this->finalidade;
    }

    public function setFinalidade(int $finalidade): self
    {
        $this->finalidade = $finalidade;

        return $this;
    }

    public function getDataEmissao(): string
    {
        if(empty($this->data_emissao)) return '';
        return date_format($this->data_emissao, 'd-m-Y H:i');
    }

    public function setDataEmissao(\DateTimeInterface $data_emissao): self
    {
        $this->data_emissao = $data_emissao;

        return $this;
    }

    public function getNumNf(): ?int
    {
        return $this->num_nf;
    }

    public function setNumNf(?int $num_nf): self
    {
        $this->num_nf = $num_nf;

        return $this;
    }

    public function getNatureza(): ?string
    {
        return $this->natureza;
    }

    public function setNatureza(?string $natureza): self
    {
        $this->natureza = $natureza;

        return $this;
    }

    public function getInfoFisco(): ?string
    {
        return $this->info_fisco;
    }

    public function setInfoFisco(?string $info_fisco): self
    {
        $this->info_fisco = $info_fisco;

        return $this;
    }

    public function getNfReferencia(): ?string
    {
        return $this->nf_referencia;
    }

    public function setNfReferencia(?string $nf_referencia): self
    {
        $this->nf_referencia = $nf_referencia;

        return $this;
    }

    public function getInfoComplementar(): ?string
    {
        return $this->info_complementar;
    }

    public function setInfoComplementar(?string $info_complementar): self
    {
        $this->info_complementar = $info_complementar;

        return $this;
    }

    /**
     * @return Collection|ComercialItens[]
     */
    public function getComercialItens(): Collection
    {
        return $this->comercialItens;
    }

    public function addComercialIten(ComercialItens $comercialIten): self
    {
        if (!$this->comercialItens->contains($comercialIten)) {
            $this->comercialItens[] = $comercialIten;
            $comercialIten->setComercial($this);
        }

        return $this;
    }

    public function removeComercialIten(ComercialItens $comercialIten): self
    {
        if ($this->comercialItens->contains($comercialIten)) {
            $this->comercialItens->removeElement($comercialIten);
            // set the owning side to null (unless already changed)
            if ($comercialIten->getComercial() === $this) {
                $comercialIten->setComercial(null);
            }
        }

        return $this;
    }

    public function getDataLancamento(): string
    {
        if(empty($this->data_lancamento)) return '';
        return date_format($this->data_lancamento, 'd-m-Y H:i');
    }

    public function setDataLancamento(\DateTimeInterface $data_lancamento): self
    {
        $this->data_lancamento = $data_lancamento;

        return $this;
    }
}
