<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ThemeRepository;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index_theme(ThemeRepository $themeRepository): Response
    {
        return $this->redirectToRoute('app_login');
    }
}
