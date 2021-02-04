<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PessoaEnderecoRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class PessoaEndereco
{
    use SoftDeleteableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups ({"pessoaendereco_default"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pessoa", inversedBy="endereco")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pessoa;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups ({"pessoaendereco_default"})
     */
    private $uf;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"pessoaendereco_default"})
     */
    private $cidade;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"pessoaendereco_default"})
     */
    private $logradouro;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"pessoaendereco_default"})
     */
    private $bairro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"pessoaendereco_default"})
     */
    private $complemento;

    /**
     * @ORM\Column(type="text")
     * @Groups ({"pessoaendereco_default"})
     */
    private $numero;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"pessoaendereco_default"})
     */
    private $cep;

    /**
     * @ORM\Column(type="integer")
     */
    private $ibge_cidade;

    public function getId(): ?int
    {
        return $this->id;
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
     * @return mixed
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param mixed $uf
     */
    public function setUf($uf): void
    {
        $this->uf = $uf;
    }

    /**
     * @return mixed
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param mixed $cidade
     */
    public function setCidade($cidade): void
    {
        $this->cidade = $cidade;
    }

    /**
     * @return mixed
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * @param mixed $logradouro
     */
    public function setLogradouro($logradouro): void
    {
        $this->logradouro = $logradouro;
    }

    /**
     * @return mixed
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param mixed $bairro
     */
    public function setBairro($bairro): void
    {
        $this->bairro = $bairro;
    }

    /**
     * @return mixed
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param mixed $complemento
     */
    public function setComplemento($complemento): void
    {
        $this->complemento = $complemento;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param mixed $cep
     */
    public function setCep($cep): void
    {
        $this->cep = $cep;
    }

    /**
     * @return mixed
     */
    public function getIbgeCidade()
    {
        return $this->ibge_cidade;
    }

    /**
     * @param mixed $ibge_cidade
     */
    public function setIbgeCidade($ibge_cidade): void
    {
        $this->ibge_cidade = $ibge_cidade;
    }

}
