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
     * @ORM\ManyToOne(targetEntity="App\Entity\TribCST", inversedBy="tribTipoOperacaoOrigem")
     */
    private $cst_origem;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TribCST", inversedBy="tribTipoOperacaoTrib")
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

    /**
     * @ORM\OneToMany(targetEntity=ComercialItens::class, mappedBy="operacao")
     */
    private $comercialItens;

    /**
     * @ORM\ManyToOne(targetEntity=TribCFOP::class, inversedBy="tribTipoOperacaos")
     */
    private $cfop;

    public function __construct()
    {
        $this->comercialItens = new ArrayCollection();
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

    public function getCstOrigem(): ?TribCST
    {
        return $this->cst_origem;
    }

    public function setCstOrigem(?TribCST $cst_origem): self
    {
        $this->cst_origem = $cst_origem;

        return $this;
    }

    public function getCstTrib(): ?TribCST
    {
        return $this->cst_trib;
    }

    public function setCstTrib(?TribCST $cst_trib): self
    {
        $this->cst_trib = $cst_trib;

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

    /**
     * @return Collection|ComercialItens[]
     */
    public function getComercialItens(): ?Collection
    {
        return $this->comercialItens;
    }

    public function addComercialIten(ComercialItens $comercialIten): self
    {
        if (!$this->comercialItens->contains($comercialIten)) {
            $this->comercialItens[] = $comercialIten;
            $comercialIten->setOperacao($this);
        }

        return $this;
    }

    public function removeComercialIten(ComercialItens $comercialIten): self
    {
        if ($this->comercialItens->contains($comercialIten)) {
            $this->comercialItens->removeElement($comercialIten);
            // set the owning side to null (unless already changed)
            if ($comercialIten->getOperacao() === $this) {
                $comercialIten->setOperacao(null);
            }
        }

        return $this;
    }

    public function getCfop(): ?TribCFOP
    {
        return $this->cfop;
    }

    public function setCfop(?TribCFOP $cfop): self
    {
        $this->cfop = $cfop;

        return $this;
    }
}
