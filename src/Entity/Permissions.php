<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PermissionsRepository")
 */
class Permissions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UsersGroup", inversedBy="permissions")
     * @ORM\JoinColumn(nullable=false)
     */
    private UsersGroup $group;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $route;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private bool $isOpen;

    public function getId(): int
    {
        return $this->id;
    }

    public function getGroup(): UsersGroup
    {
        return $this->group;
    }

    public function setGroup(UsersGroup $group): self
    {
        $this->group = $group;
        return $this;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;
        return $this;
    }

    public function getIsOpen(): bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): self
    {
        $this->isOpen = $isOpen;
        return $this;
    }
}
