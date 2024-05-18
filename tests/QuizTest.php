<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Repository\AnswerRepository;
use App\Entity\Answer;
use App\Entity\Quiz;
use App\Entity\Question;
use App\Controller\QuizController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuizTest extends KernelTestCase
{
    private Quiz $quiz;
    private Answer $answer1;
    private Answer $answer2;
    private Question $question1;
    private Question $question2;

    protected function setUp() : void
    {
        parent::setUp();
        $this->quiz = new Quiz();
        $this->answer1 = new Answer();
        $this->answer2 = new Answer();
        $this->question1 = new Question();
        $this->question2 = new Question();
    }

    /** @test */
    public function QcmTest(): void
    {
        $answers = [];

        $this->answer1->setIsCorrect(true);
        $answers[] = $this->answer1;

        $this->answer2->setIsCorrect(false);
        $answers[] = $this->answer2;
        
        $this->question1->addAnswer(($this->answer1));
        $this->quiz->addQuestion(($this->question1));

        $this->question2->addAnswer(($this->answer2));
        $this->quiz->addQuestion(($this->question2));

        $quizcontroller = new QuizController();
        $score = $quizcontroller->getResult($answers);

        $this->assertEquals($score, 50, "le quiz ne retourne pas le bon score");
        $this->assertNotEquals($score, 100, "le quiz ne retourne pas le bon score");

    }
}