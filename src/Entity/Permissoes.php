<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PermissoesRepository")
 */
class Permissoes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GrupoUsuarios", inversedBy="permissoes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grupo;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\JoinColumn(nullable=false)
     */
    private $rota;

    /**
     * @ORM\Column(type="boolean")
     * @ORM\JoinColumn(nullable=false)
     */
    private $liberado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrupo(): ?GrupoUsuarios
    {
        return $this->grupo;
    }

    public function setGrupo(?GrupoUsuarios $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function getRota(): ?string
    {
        return $this->rota;
    }

    public function setRota(string $rota): self
    {
        $this->rota = $rota;

        return $this;
    }

    public function getLiberado(): ?bool
    {
        return $this->liberado;
    }

    public function setLiberado(bool $liberado): self
    {
        $this->liberado = $liberado;

        return $this;
    }
}
