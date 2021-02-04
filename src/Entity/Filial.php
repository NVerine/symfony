<?php

namespace App\Entity;

use App\Repository\FilialRepository;
use App\Util\ValueHelper;
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
     * @ORM\OneToOne(targetEntity=Pessoa::class, cascade={"persist", "remove"})
     */
    private $pessoa;

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

    public function getPessoa(): ?Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(?Pessoa $pessoa): self
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getNomeFantasia()
    {
        return $this->getPessoa()->getNomeFantasia();
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getRazaosocial()
    {
        return $this->getPessoa()->getNome();
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getCnpj()
    {
        return $this->getPessoa()->getCpfCnpj();
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getEnderecoCompleto(): ?string
    {
        return $this->getPessoa()->getEnderecoCompleto();
    }

    /**
     * @Groups({"filial_index"})
     */
    public function getContatoCompleto(): ?string
    {
        return $this->getPessoa()->getContatoCompleto();
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
