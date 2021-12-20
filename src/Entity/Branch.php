<?php

namespace App\Entity;

use App\Repository\BranchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BranchRepository::class)
 */
class Branch
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $timezone;

    /**
     * @ORM\OneToMany(targetEntity="Person", mappedBy="branch")
     */
    private $people; // este é o array de pessoas registradas nessa filial

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="branchs")
     */
    private $owner; // este é a pessoa DONA desta filial

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="branchs")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="activeBranch")
     */
    private $usersInBranch;

    public function __construct() {
        $this->people = new ArrayCollection();
        $this->usersInBranch = new ArrayCollection();
        $this->users = new ArrayCollection();
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
            $user->addBranchs($this);
        }
        return $this;
    }
    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeBranchs($this);
        }
        return $this;
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

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;
        return $this;
    }

    public function getOwner(): ?Person
    {
        return $this->owner;
    }

    public function setOwner(?Person $person): self
    {
        $this->owner = $person;
        return $this;
    }

    public function getPeople(): ?Collection
    {
        return $this->people;
    }

    public function setPeople(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->setBranch($this);
        }

        return $this;
    }

    public function removePeople(Person $person): self
    {
        if ($this->people->contains($person)) {
            $this->people->removeElement($person);
            // set the owning side to null (unless already changed)
            if ($person->getBranch() === $this) {
                $person->setBranch(null);
            }
        }

        return $this;
    }

    public function getUsersInBranch(): ?Collection
    {
        return $this->usersInBranch;
    }

    public function setUsersInBranch(User $user): self
    {
        if (!$this->usersInBranch->contains($user)) {
            $this->usersInBranch[] = $user;
            $user->setActiveBranch($this);
        }

        return $this;
    }

    public function removeUsersInBranch(User $user): self
    {
        if ($this->usersInBranch->contains($user)) {
            $this->usersInBranch->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getActiveBranch() === $this) {
                $user->setActiveBranch(null);
            }
        }

        return $this;
    }
}
