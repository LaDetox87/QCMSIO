<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublished = null;

    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'quiz')]
    private Collection $Questions;

    #[ORM\ManyToOne(inversedBy: 'Quizzes', cascade: ["persist"])]
    private ?Theme $theme = null;

    #[ORM\OneToMany(targetEntity: Result::class, mappedBy: 'quiz')]
    private Collection $Results;

    #[ORM\Column]
    private ?bool $isGraded = null;

    public function __construct()
    {
        $this->Questions = new ArrayCollection();
        $this->Results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->Questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->Questions->contains($question)) {
            $this->Questions->add($question);
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->Questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return Collection<int, Result>
     */
    public function getResults(): Collection
    {
        return $this->Results;
    }

    public function addResult(Result $result): static
    {
        if (!$this->Results->contains($result)) {
            $this->Results->add($result);
            $result->setQuiz($this);
        }

        return $this;
    }

    public function removeResult(Result $result): static
    {
        if ($this->Results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getQuiz() === $this) {
                $result->setQuiz(null);
            }
        }

        return $this;
    }

    public function isIsGraded(): ?bool
    {
        return $this->isGraded;
    }

    public function setIsGraded(bool $isGraded): static
    {
        $this->isGraded = $isGraded;

        return $this;
    }
}
