<?php

namespace App\Entity;

use App\Entity\Traits\PersonAddressTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonAddressRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class PersonAddress
{
    use SoftDeleteableEntity, PersonAddressTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="address")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(Person $person): self
    {
        $this->person = $person;
        return $this;
    }

}
