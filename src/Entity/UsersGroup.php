<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersGroupRepository")
 */
class UsersGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Permissions", mappedBy="group", orphanRemoval=true)
     */
    private $permissions;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUsers(): ?Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getGroup() === $this) {
                $user->setGroup(null);
            }
        }

        return $this;
    }

    public function getPermissions(): ?Collection
    {
        return $this->permissions;
    }

    public function addPermissions(Permissions $permissions): self
    {
        if (!$this->permissions->contains($permissions)) {
            $this->permissions[] = $permissions;
            $permissions->setGroup($this);
        }

        return $this;
    }

    public function removePermissions(Permissions $permissions): self
    {
        if ($this->permissions->contains($permissions)) {
            $this->permissions->removeElement($permissions);
            // set the owning side to null (unless already changed)
            if ($permissions->getGroup() === $this) {
                $permissions->setGroup(null);
            }
        }

        return $this;
    }
}
