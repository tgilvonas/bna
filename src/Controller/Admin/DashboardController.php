<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    protected ArticleRepository $articleRepository;

    protected CategoryRepository $categoryRepository;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/admin/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        $articlesByCategory = $this->categoryRepository->getCategoriesQuery(['published' => true], true)->getResult();
        $articlesByYear = $this->articleRepository->getSumsOfArticlesByYear();

        return $this->render('dashboard/index.html.twig', [
            'articlesByCategory' => $articlesByCategory,
            'articlesByYear' => $articlesByYear,
        ]);
    }
}
