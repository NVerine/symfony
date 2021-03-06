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
     * @Groups ({"user_default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups ({"user_default"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GrupoUsuarios", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grupo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pessoa", inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pessoa;

     /**
      * @ORM\OneToMany(targetEntity=UserTokens::class, mappedBy="user", orphanRemoval=true)
      */
     private $userTokens;

    /**
     * @ORM\ManyToMany(targetEntity="Filial", inversedBy="usuarios")
     */
    private $filiais;

    /**
     * @ORM\ManyToOne(targetEntity="Filial", inversedBy="usuariosNaFilial")
     * @ORM\JoinColumn(nullable=true)
     */
    private $filialAtiva;

     public function __construct()
     {
         $this->userTokens = new ArrayCollection();
         $this->filiais = new ArrayCollection();
     }

    /**
     * @return Collection|Filial[]
     */
    public function getFiliais(): ?Collection
    {
        return $this->filiais;
    }
    public function addFilais(Filial $filial): self
    {
        if (!$this->filiais->contains($filial)) {
            $this->filiais[] = $filial;
        }
        return $this;
    }
    public function removeFiliais(Filial $filial): self
    {
        if ($this->filiais->contains($filial)) {
            $this->filiais->removeElement($filial);
        }
        return $this;
    }

    /**
     * @return mixed
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
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

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getGrupo(): ?GrupoUsuarios
    {
        return $this->grupo;
    }

    public function setGrupo(?GrupoUsuarios $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function getNomeGrupo(): string
    {
        if(!empty($this->grupo))
            return (string) $this->grupo->getNome();
        return '';
    }

    public function getPessoa(): ?Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa): self
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    public function getFilialAtiva(): ?Filial
    {
        return $this->filialAtiva;
    }

    public function setFilialAtiva(Filial $filial): self
    {
        $this->filialAtiva = $filial;
        return $this;
    }

    /**
     * @return Collection|UserTokens[]
     */
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
