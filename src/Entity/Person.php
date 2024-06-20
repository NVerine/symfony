<?php

namespace App\Entity;

use App\Entity\Traits\PersonTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    use PersonTrait;

    /**
     * @ORM\OneToMany(targetEntity="PersonAddress", mappedBy="person")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="PersonContact", mappedBy="person")
     */
    private $contact;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="person", fetch="LAZY")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Branch", inversedBy="people")
     */
    private $branch; //esse é a relação de onde a pessoa esta registrada

    /**
     * @ORM\OneToMany(targetEntity="Branch", mappedBy="owner")
     */
    private $branchs; // este é o array de filiais em que esta pessoa é dona

    /**
     * @ORM\OneToOne(targetEntity=PersonContact::class, cascade={"persist", "remove"})
     */
    private $mainContact;

    /**
     * @ORM\OneToOne(targetEntity=PersonAddress::class, cascade={"persist", "remove"})
     */
    private $mainAddress;

    public function __construct()
    {
        $this->address = new ArrayCollection();
        $this->contact = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->branchs = new ArrayCollection();
    }

    /**
     * @return Collection|PersonAddress[]
     */
    public function getAddress(): ?Collection
    {
        return $this->address;
    }

    public function addAddress(PersonAddress $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setPerson($this);
        }

        return $this;
    }

    public function removeAddress(PersonAddress $address): self
    {
        if ($this->address->contains($address)) {
            $this->address->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getPerson() === $this) {
                $address->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PersonContact[]
     */
    public function getContact(): ?Collection
    {
        return $this->contact;
    }

    public function addContact(PersonContact $contact): self
    {
        if (!$this->contact->contains($contact)) {
            $this->contact[] = $contact;
            $contact->setPerson($this);
        }

        return $this;
    }

    public function removeContact(PersonContact $contact): self
    {
        if ($this->contact->contains($contact)) {
            $this->contact->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getPerson() === $this) {
                $contact->setPerson(null);
            }
        }

        return $this;
    }

    public function getUser(): ?Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setPerson($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPerson() === $this) {
                $user->setPerson(null);
            }
        }

        return $this;
    }

    public function getBranchs(): ?Collection
    {
        return $this->branchs;
    }

    public function addBranchs(Branch $branch): self
    {
        if (!$this->branchs->contains($branch)) {
            $this->branchs[] = $branch;
            $branch->setOwner($this);
        }

        return $this;
    }

    public function removeBranchs(Branch $branch): self
    {
        if ($this->branchs->contains($branch)) {
            $this->branchs->removeElement($branch);
            // set the owning side to null (unless already changed)
            if ($branch->getOwner() === $this) {
                $branch->setOwner(null);
            }
        }

        return $this;
    }

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(?Branch $branch): self
    {
        $this->branch = $branch;
        return $this;
    }

    public function getMainAddress(): ?PersonAddress
    {
        return $this->mainAddress;
    }

    public function setMainAddress(?PersonAddress $mainAddress): self
    {
        $this->mainAddress = $mainAddress;
        return $this;
    }

    public function getMainContact(): ?PersonContact
    {
        return $this->mainContact;
    }

    public function setMainContact(?PersonContact $mainContact): self
    {
        $this->mainContact = $mainContact;
        return $this;
    }
}
