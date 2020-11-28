<?php

namespace App\Entity;

use App\Repository\TribTipoOperacaoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TribTipoOperacaoRepository::class)
 */
class TribTipoOperacao
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
     * @ORM\Column(type="integer")
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descricao;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity=TribCST::class, mappedBy="tribTipoOperacaoOrigem")
     */
    private $cst_origem;

    /**
     * @ORM\OneToMany(targetEntity=TribNCM::class, mappedBy="tribTipoOperacaoTrib")
     */
    private $cst_trib;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $csosn;

    /**
     * @ORM\Column(type="integer")
     */
    private $icmstipo;

    /**
     * @ORM\Column(type="float")
     */
    private $icmsbase;

    /**
     * @ORM\Column(type="float")
     */
    private $pisaliquota;

    /**
     * @ORM\Column(type="float")
     */
    private $cofinsaliquota;

    /**
     * @ORM\Column(type="float")
     */
    private $issqnaliquota;

    public function __construct()
    {
        $this->cst_origem = new ArrayCollection();
        $this->cst_trib = new ArrayCollection();
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

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = $codigo;

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
     * @return Collection|TribCST[]
     */
    public function getCstOrigem(): Collection
    {
        return $this->cst_origem;
    }

    public function addCstOrigem(TribCST $cstOrigem): self
    {
        if (!$this->cst_origem->contains($cstOrigem)) {
            $this->cst_origem[] = $cstOrigem;
            $cstOrigem->setTribTipoOperacaoOrigem($this);
        }

        return $this;
    }

    public function removeCstOrigem(TribCST $cstOrigem): self
    {
        if ($this->cst_origem->contains($cstOrigem)) {
            $this->cst_origem->removeElement($cstOrigem);
            // set the owning side to null (unless already changed)
            if ($cstOrigem->getTribTipoOperacaoOrigem() === $this) {
                $cstOrigem->setTribTipoOperacaoOrigem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TribNCM[]
     */
    public function getCstTrib(): Collection
    {
        return $this->cst_trib;
    }

    public function addCstTrib(TribNCM $cstTrib): self
    {
        if (!$this->cst_trib->contains($cstTrib)) {
            $this->cst_trib[] = $cstTrib;
            $cstTrib->setTribTipoOperacaoTrib($this);
        }

        return $this;
    }

    public function removeCstTrib(TribNCM $cstTrib): self
    {
        if ($this->cst_trib->contains($cstTrib)) {
            $this->cst_trib->removeElement($cstTrib);
            // set the owning side to null (unless already changed)
            if ($cstTrib->getTribTipoOperacaoTrib() === $this) {
                $cstTrib->setTribTipoOperacaoTrib(null);
            }
        }

        return $this;
    }

    public function getCsosn(): ?int
    {
        return $this->csosn;
    }

    public function setCsosn(?int $csosn): self
    {
        $this->csosn = $csosn;

        return $this;
    }

    public function getIcmstipo(): ?int
    {
        return $this->icmstipo;
    }

    public function setIcmstipo(int $icmstipo): self
    {
        $this->icmstipo = $icmstipo;

        return $this;
    }

    public function getIcmsbase(): ?float
    {
        return $this->icmsbase;
    }

    public function setIcmsbase(float $icmsbase): self
    {
        $this->icmsbase = $icmsbase;

        return $this;
    }

    public function getPisaliquota(): ?float
    {
        return $this->pisaliquota;
    }

    public function setPisaliquota(float $pisaliquota): self
    {
        $this->pisaliquota = $pisaliquota;

        return $this;
    }

    public function getCofinsaliquota(): ?float
    {
        return $this->cofinsaliquota;
    }

    public function setCofinsaliquota(float $cofinsaliquota): self
    {
        $this->cofinsaliquota = $cofinsaliquota;

        return $this;
    }

    public function getIssqnaliquota(): ?float
    {
        return $this->issqnaliquota;
    }

    public function setIssqnaliquota(float $issqnaliquota): self
    {
        $this->issqnaliquota = $issqnaliquota;

        return $this;
    }
}
