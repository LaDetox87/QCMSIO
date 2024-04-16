<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/answer')]
class AnswerController extends AbstractController
{
    #[Route('/', name: 'app_answer_index', methods: ['GET'])]
    public function index(AnswerRepository $answerRepository): Response
    {
        return $this->render('answer/index.html.twig', [
            'answers' => $answerRepository->findAll(),
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut créer des réponses")]
    #[Route('/new', name: 'app_answer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, QuestionRepository $questionRepository): Response
    {
        $idquestion = $_GET['idquestion'];
        $question = $questionRepository->findOneBy(["id" => $idquestion]);
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setQuestion($question);
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('app_question_answers', ['id' => $idquestion], Response::HTTP_SEE_OTHER);
        }

        return $this->render('answer/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
            'idquestion' => $idquestion,
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut show des réponses")]
    #[Route('/{id}', name: 'app_answer_show', methods: ['GET'])]
    public function show(Answer $answer): Response
    {
        return $this->render('answer/show.html.twig', [
            'answer' => $answer,
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut edit des réponses")]
    #[Route('/{id}/edit', name: 'app_answer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Answer $answer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[IsGranted("ROLE_ADMIN", message:"Seul un admin peut supprimer des réponses")]
    #[Route('/{id}', name: 'app_answer_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Answer $answer, EntityManagerInterface $entityManager, QuestionRepository $questionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($answer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
    }
}