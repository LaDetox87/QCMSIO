<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'app_question_index', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    #[Route('/{id}/answers', name: 'app_question_answers', methods: ['GET'])]
    public function questionAnswers(AnswerRepository $answerRepository, QuestionRepository $questionRepository, int $id): Response
    {
        return $this->render('question/answers.html.twig', [
            'answers' => $answerRepository->findByQuestion($id),
            'question' => $questionRepository->findOneBy(["id" => $id])
        ]);
    }

    
    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut créer des questions")]
    #[Route('/new', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, QuizRepository $quizRepository): Response
    {
        $idquiz = $_GET['idquiz'];
        $quiz = $quizRepository->findOneBy(["id" => $idquiz]);
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setQuiz($quiz);
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_questions', ['id' => $idquiz], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form,
            'idquiz' => $idquiz,
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut show des questions")]
    #[Route('/{id}', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut edit des questions")]
    #[Route('/{id}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut supprimer des questions")]
    #[Route('/{id}', name: 'app_question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
    }
}
