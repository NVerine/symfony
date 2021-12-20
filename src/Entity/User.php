<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="UsersGroup", inversedBy="users")
     */
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="user", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

     /**
      * @ORM\OneToMany(targetEntity=UserTokens::class, mappedBy="user", orphanRemoval=true)
      */
     private $userTokens;

    /**
     * @ORM\ManyToMany(targetEntity="Branch", inversedBy="users")
     */
    private $branchs;

    /**
     * @ORM\ManyToOne(targetEntity="Branch", inversedBy="usersInBranch")
     * @ORM\JoinColumn(nullable=true)
     */
    private $activeBranch;

     public function __construct()
     {
         $this->userTokens = new ArrayCollection();
         $this->branchs = new ArrayCollection();
     }

    public function getBranchs(): ?Collection
    {
        return $this->branchs;
    }
    public function addBranchs(Branch $branch): self
    {
        if (!$this->branchs->contains($branch)) {
            $this->branchs[] = $branch;
        }
        return $this;
    }
    public function removeBranchs(Branch $branch): self
    {
        if ($this->branchs->contains($branch)) {
            $this->branchs->removeElement($branch);
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getGroup(): ?UsersGroup
    {
        return $this->group;
    }

    public function setGroup(UsersGroup $group): self
    {
        $this->group = $group;
        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getActiveBranch(): ?Branch
    {
        return $this->activeBranch;
    }

    public function setActiveBranch(Branch $filial): self
    {
        $this->activeBranch = $filial;
        return $this;
    }

    public function getUserTokens(): ?Collection
    {
        return $this->userTokens;
    }

    public function addUserToken(UserTokens $userToken): self
    {
        if (!$this->userTokens->contains($userToken)) {
            $this->userTokens[] = $userToken;
            $userToken->setUser($this);
        }

        return $this;
    }

    public function removeUserToken(UserTokens $userToken): self
    {
        if ($this->userTokens->contains($userToken)) {
            $this->userTokens->removeElement($userToken);
            // set the owning side to null (unless already changed)
            if ($userToken->getUser() === $this) {
                $userToken->setUser(null);
            }
        }

        return $this;
    }
}
