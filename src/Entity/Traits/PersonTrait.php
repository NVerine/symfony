<?php
namespace App\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait PersonTrait
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
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isCustomer = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isSupplier = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isEmployee = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations)
    {
        $this->observations = $observations;
        return $this;
    }

    public function getBirthDate(): String
    {
        if(empty($this->birthDate)) return '';
        return date_format($this->birthDate, 'd-m-Y');
    }

    public function setBirthDate(?DateTimeInterface $birthDate)
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsCustomer(): ?bool
    {
        return $this->isCustomer;
    }

    public function setIsCustomer(bool $isCustomer)
    {
        $this->isCustomer = $isCustomer;
        return $this;
    }

    public function getIsSupplier(): ?bool
    {
        return $this->isSupplier;
    }

    public function setIsSupplier(bool $isSupplier)
    {
        $this->isSupplier = $isSupplier;
        return $this;
    }

    public function getIsEmployee(): ?bool
    {
        return $this->isEmployee;
    }

    public function setIsEmployee(bool $isEmployee)
    {
        $this->isEmployee = $isEmployee;
        return $this;
    }
}