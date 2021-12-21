<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait PersonAddressTrait
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $uf;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $district;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressComplement;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $number;

    /**
     * @ORM\Column(type="integer")
     */
    private $zip;

    /**
     * @ORM\Column(type="integer")
     */
    private $ibge_cidade;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(string $uf): self
    {
        $this->uf = $uf;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity($city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress($address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict($district): self
    {
        $this->district = $district;
        return $this;
    }

    public function getAddressComplement(): ?string
    {
        return $this->addressComplement;
    }

    public function setAddressComplement($addressComplement): self
    {
        $this->addressComplement = $addressComplement;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function setZip($zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    public function getIbgeCidade(): ?int
    {
        return $this->ibge_cidade;
    }

    public function setIbgeCidade($ibge_cidade): self
    {
        $this->ibge_cidade = $ibge_cidade;
        return $this;
    }
}