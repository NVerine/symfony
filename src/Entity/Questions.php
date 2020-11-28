<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionsRepository::class)
 */
class Questions
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answer;

    /**
     * @ORM\OneToMany(targetEntity=QuestionsOPT::class, mappedBy="question")
     */
    private $opt;

    /**
     * @ORM\OneToMany(targetEntity=QuizQuestions::class, mappedBy="question")
     */
    private $quizQuestions;

    /**
     * @ORM\ManyToOne(targetEntity=Disciplina::class, inversedBy="questions")
     */
    private $disciplina;

    /**
     * type of questions
     */
    const text = 1;
    const number = 2;
    const select = 3;
    const radiobutton = 4;
    const checkbox = 5;
//    const datetime = 6;
//    const date = 7;
//    const time = 8;

    public function __construct()
    {
        $this->opt = new ArrayCollection();
        $this->quizQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * @return Collection|QuestionsOPT[]
     */
    public function getOpt(): Collection
    {
        return $this->opt;
    }

    public function addText(QuestionsOPT $text): self
    {
        if (!$this->opt->contains($text)) {
            $this->opt[] = $text;
            $text->setQuestion($this);
        }

        return $this;
    }

    public function removeText(QuestionsOPT $text): self
    {
        if ($this->opt->contains($text)) {
            $this->opt->removeElement($text);
            // set the owning side to null (unless already changed)
            if ($text->getQuestion() === $this) {
                $text->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QuizQuestions[]
     */
    public function getQuizQuestions(): Collection
    {
        return $this->quizQuestions;
    }

    public function addQuizQuestion(QuizQuestions $quizQuestion): self
    {
        if (!$this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions[] = $quizQuestion;
            $quizQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeQuizQuestion(QuizQuestions $quizQuestion): self
    {
        if ($this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions->removeElement($quizQuestion);
            // set the owning side to null (unless already changed)
            if ($quizQuestion->getQuestion() === $this) {
                $quizQuestion->setQuestion(null);
            }
        }

        return $this;
    }

    public function getDisciplina(): ?Disciplina
    {
        return $this->disciplina;
    }

    public function setDisciplina(?Disciplina $disciplina): self
    {
        $this->disciplina = $disciplina;

        return $this;
    }
}
