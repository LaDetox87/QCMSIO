<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizType;
use App\Repository\AnswerRepository;
use App\Repository\QuizRepository;
use App\Repository\QuestionRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    #[Route('/', name: 'app_quiz_index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }

    #[Route('/startquiz/{id}', name: 'app_start_quiz', methods: ['GET'])]
    public function startQuiz(QuestionRepository $questionRepository, int $id, QuizRepository $quizRepository): Response
    {
        return $this->render('quiz/startquiz.html.twig', [
            'questions' => $questionRepository->findByQuiz($id),
            'quiz' => $quizRepository->findOneBy(["id" => $id]),
        ]);
    }

    #[Route('/quizsubmit/{id}', name: 'app_quiz_submit', methods: ['GET','POST'])]
    public function submitQuiz(QuizRepository $quizRepository, int $id, AnswerRepository $answerRepository): Response
    {
        $quiz = $quizRepository->findOneBy(["id" => $id]);

        $questions = $quiz->getQuestions();

        $nbquestion = Count($questions);

        $score = 0;

        foreach($questions as $question){
            $answers=[];

            $questionstring = 'question'.$question->getId();

            $idanswer = $_POST[$questionstring];

            $answer = $answerRepository->findOneBy(["id" => $idanswer]);

            $answers[] = $answer->GetId();

            $score = $answer->isIsCorrect() ? $score + (100/$nbquestion) : $score;
        }

        return $this->render('quiz/submitquiz.html.twig', [
            'quiz' => $quiz,
            'score' => $score,
            'answers' => $answers,
        ]);
    }

    #[Route('/{id}/questions', name: 'app_quiz_questions', methods: ['GET'])]
    public function quizQuestions(QuestionRepository $questionRepository, QuizRepository $quizRepository, int $id): Response
    {
        return $this->render('quiz/questions.html.twig', [
            'questions' => $questionRepository->findByQuiz($id),
            'quiz' => $quizRepository->findOneBy(["id" => $id]),
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut créer des quiz")]
    #[Route('/new', name: 'app_quiz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ThemeRepository $themeRepository): Response
    {
        $idtheme = $_GET['idtheme'];
        $theme = $themeRepository->findOneBy(["id" => $idtheme]);
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quiz->setTheme($theme);
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute("app_theme_quizzes", ['id' => $idtheme], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
            'idtheme' => $idtheme,
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut show des quiz")]
    #[Route('/{id}', name: 'app_quiz_show', methods: ['GET'])]
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut edit des quiz")]
    #[Route('/{id}/edit', name: 'app_quiz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut supprimer des quiz")]
    #[Route('/{id}', name: 'app_quiz_delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
    }
}
