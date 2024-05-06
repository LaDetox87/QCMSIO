<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lesson = null;

    #[ORM\OneToMany(targetEntity: Quiz::class, mappedBy: 'theme', cascade: ['persist'])]
    private Collection $Quizzes;

    public function __construct()
    {
        $this->Quizzes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getLesson(): ?string
    {
        return $this->lesson;
    }

    public function setLesson(?string $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuizzes(): Collection
    {
        return $this->Quizzes;
    }

    public function addQuiz(Quiz $quiz): static
    {
        if (!$this->Quizzes->contains($quiz)) {
            $this->Quizzes->add($quiz);
            $quiz->setTheme($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): static
    {
        if ($this->Quizzes->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getTheme() === $this) {
                $quiz->setTheme(null);
            }
        }

        return $this;
    }
}
