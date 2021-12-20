<?php

namespace App\Entity;

use App\Repository\ReportsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportsRepository::class)
 */
class Reports
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $columnName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $columnNameReplacer;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $columnOrder;

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

    public function getColumnName(): ?string
    {
        return $this->columnName;
    }

    public function setColumnName(string $columnName): self
    {
        $this->columnName = $columnName;

        return $this;
    }

    public function getColumnNameReplacer(): ?string
    {
        return $this->columnNameReplacer;
    }

    public function setColumnNameReplacer(?string $columnNameReplacer): self
    {
        $this->columnNameReplacer = $columnNameReplacer;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getColumnOrder(): ?int
    {
        return $this->columnOrder;
    }

    public function setColumnOrder(int $columnOrder): self
    {
        $this->columnOrder = $columnOrder;

        return $this;
    }
}
