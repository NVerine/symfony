<?php

namespace App\Entity;

use App\Util\ValueHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProdutoRepository")
 */
class Produto
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
     * @ORM\Column(type="float")
     */
    private $preco;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FamiliaProduto", inversedBy="produtos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $familia;

    /**
     * @ORM\OneToMany(targetEntity=TribNCM::class, mappedBy="produto")
     */
    private $ncm;

    /**
     * @ORM\OneToMany(targetEntity=ComercialItens::class, mappedBy="produto")
     */
    private $comercialItens;

    public function __construct()
    {
        $this->ncm = new ArrayCollection();
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

    public function getPreco(): ?string
    {
        return ValueHelper::intToMoney($this->preco);
    }

    public function setPreco(float $preco): self
    {
        $this->preco = $preco;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFamilia(): ?FamiliaProduto
    {
        return $this->familia;
    }

    public function setFamilia(?FamiliaProduto $familia): self
    {
        $this->familia = $familia;

        return $this;
    }

    /**
     * @return Collection|TribNCM[]
     */
    public function getNcm(): ?Collection
    {
        return $this->ncm;
    }

    public function addNcm(TribNCM $ncm): self
    {
        if (!$this->ncm->contains($ncm)) {
            $this->ncm[] = $ncm;
            $ncm->setProduto($this);
        }

        return $this;
    }

    public function removeNcm(TribNCM $ncm): self
    {
        if ($this->ncm->contains($ncm)) {
            $this->ncm->removeElement($ncm);
            // set the owning side to null (unless already changed)
            if ($ncm->getProduto() === $this) {
                $ncm->setProduto(null);
            }
        }

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
            $comercialIten->setProduto($this);
        }

        return $this;
    }

    public function removeComercialIten(ComercialItens $comercialIten): self
    {
        if ($this->comercialItens->contains($comercialIten)) {
            $this->comercialItens->removeElement($comercialIten);
            // set the owning side to null (unless already changed)
            if ($comercialIten->getProduto() === $this) {
                $comercialIten->setProduto(null);
            }
        }

        return $this;
    }

}
