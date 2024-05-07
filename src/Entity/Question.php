<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'Questions', cascade: ["persist"])]
    private ?Quiz $quiz = null;

    #[ORM\OneToMany(targetEntity: Answer::class, mappedBy: 'question')]
    private Collection $Answers;

    public function __construct()
    {
        $this->Answers = new ArrayCollection();
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


    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): static
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->Answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->Answers->contains($answer)) {
            $this->Answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function getCorrectAnswers() : array
    {
        $answers = [];
        foreach($this->getAnswers() as $answer) {
            if($answer->isIsCorrect()) {
                $answers[] = $answer;
            }
        }

        return $answers;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->Answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    public function __ToString()
    {
        return $this->getLibelle();
    }

    public function __ToJson()
    {
        $answersjson = [];
        foreach ($this->Answers as $answer){
            $answersjson[] = $answer->__ToJson();
        }
        return [
            "id" => $this->id,
            "libelle" => $this->libelle,
            "answers" => $answersjson,
        ];
    }

}
