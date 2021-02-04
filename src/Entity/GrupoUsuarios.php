<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GrupoUsuariosRepository")
 */
class GrupoUsuarios
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\JoinColumn(nullable=false)
     */
    private $nome;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="grupo")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Permissoes", mappedBy="grupo", orphanRemoval=true)
     */
    private $permissoes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissoes = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): ?Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setGrupo($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getGrupo() === $this) {
                $user->setGrupo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Permissoes[]
     */
    public function getPermissoes(): ?Collection
    {
        return $this->permissoes;
    }

    public function addPermisso(Permissoes $permisso): self
    {
        if (!$this->permissoes->contains($permisso)) {
            $this->permissoes[] = $permisso;
            $permisso->setGrupo($this);
        }

        return $this;
    }

    public function removePermisso(Permissoes $permisso): self
    {
        if ($this->permissoes->contains($permisso)) {
            $this->permissoes->removeElement($permisso);
            // set the owning side to null (unless already changed)
            if ($permisso->getGrupo() === $this) {
                $permisso->setGrupo(null);
            }
        }

        return $this;
    }
}
