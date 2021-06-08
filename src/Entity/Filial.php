<?php

namespace App\Entity;

use App\Repository\FilialRepository;
use App\Util\ValueHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FilialRepository::class)
 */
class Filial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups ({"filial_default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"filial_default"})
     */
    private $nome;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"filial_default"})
     */
    private $regimeTributario;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"filial_default"})
     */
    private $timezone;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups ({"filial_default"})
     */
    private $pulaNf;

    /**
     * @ORM\OneToMany(targetEntity="Pessoa", mappedBy="filial")
     */
    private $pessoas; // este é o array de pessoas registradas nessa filial

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="filiais")
     */
    private $socio; // este é a pessoa DONA desta filial

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="filiais")
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="filialAtiva")
     */
    private $usuariosNaFilial;

    public function __construct() {
        $this->pessoas = new ArrayCollection();
        $this->usuariosNaFilial = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): ?Collection
    {
        return $this->usuarios;
    }
    public function addUser(User $user): self
    {
        if (!$this->usuarios->contains($user)) {
            $this->usuarios[] = $user;
            $user->addFilais($this);
        }
        return $this;
    }
    public function removeUser(User $user): self
    {
        if ($this->usuarios->contains($user)) {
            $this->usuarios->removeElement($user);
            $user->removeFiliais($this);
        }
        return $this;
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

    public function getRegimeTributario(): ?int
    {
        return $this->regimeTributario;
    }

    public function setRegimeTributario(int $regimeTributario): self
    {
        $this->regimeTributario = $regimeTributario;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getPulaNf(): ?int
    {
        return $this->pulaNf;
    }

    public function setPulaNf(?int $pulaNf): self
    {
        $this->pulaNf = $pulaNf;

        return $this;
    }

    public function getSocio(): ?Pessoa
    {
        return $this->socio;
    }

    public function setSocio(?Pessoa $pessoa): self
    {
        $this->socio = $pessoa;

        return $this;
    }

    public function getPessoas(): ?Collection
    {
        return $this->pessoas;
    }

    public function setPessoas(Pessoa $pessoa): self
    {
        if (!$this->pessoas->contains($pessoa)) {
            $this->pessoas[] = $pessoa;
            $pessoa->setFilial($this);
        }

        return $this;
    }

    public function removePessoas(Pessoa $pessoa): self
    {
        if ($this->pessoas->contains($pessoa)) {
            $this->pessoas->removeElement($pessoa);
            // set the owning side to null (unless already changed)
            if ($pessoa->getFilial() === $this) {
                $pessoa->setFilial(null);
            }
        }

        return $this;
    }

    public function getUsuariosNaFilial(): ?Collection
    {
        return $this->usuariosNaFilial;
    }

    public function setUsuariosNaFilial(User $user): self
    {
        if (!$this->usuariosNaFilial->contains($user)) {
            $this->usuariosNaFilial[] = $user;
            $user->setFilialAtiva($this);
        }

        return $this;
    }

    public function removeUsuariosNaFilial(User $user): self
    {
        if ($this->usuariosNaFilial->contains($user)) {
            $this->usuariosNaFilial->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getFilialAtiva() === $this) {
                $user->setFilialAtiva(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getNomeFantasia(): ?string
    {
        if(!empty($this->getSocio()))
            return $this->getSocio()->getNomeFantasia();
        return '';
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getRazaosocial(): ?string
    {
        if(!empty($this->getSocio()))
            return $this->getSocio()->getNome();
        return '';
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getCnpj(): ?string
    {
        if(!empty($this->getSocio()))
            return $this->getSocio()->getCpfCnpj();
        return '';
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getEnderecoCompleto(): ?string
    {
        if(!empty($this->getSocio()))
            return $this->getSocio()->getEnderecoCompleto();
        return '';
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getContatoCompleto(): ?string
    {
        if(!empty($this->getSocio()))
            return $this->getSocio()->getContatoCompleto();
        return '';
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getNomeRegime()
    {
        if($this->regimeTributario == 1){
            return "Simples nacional";
        }
        if($this->regimeTributario == 2){
            return "Simples nacional - Excesso de sublimite";
        }
        if($this->regimeTributario == 3){
            return "Regime normal";
        }
        return "";
    }
}
