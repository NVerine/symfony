<?php

namespace App\Entity;

use App\Repository\DisciplinaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DisciplinaRepository::class)
 */
class Disciplina
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
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
    private $segmento;

    /**
     * @ORM\Column(type="boolean")
     */
    private $obrigatorio;

    /**
     * @ORM\OneToMany(targetEntity=Questions::class, mappedBy="disciplina")
     */
    private $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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

    public function getSegmento(): ?string
    {
        return $this->segmento;
    }

    public function setSegmento(string $segmento): self
    {
        $this->segmento = $segmento;

        return $this;
    }

    public function getObrigatorio(): ?bool
    {
        return $this->obrigatorio;
    }

    public function setObrigatorio(bool $obrigatorio): self
    {
        $this->obrigatorio = $obrigatorio;

        return $this;
    }

    /**
     * @return Collection|Questions[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Questions $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setDisciplina($this);
        }

        return $this;
    }

    public function removeQuestion(Questions $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getDisciplina() === $this) {
                $question->setDisciplina(null);
            }
        }

        return $this;
    }
}
