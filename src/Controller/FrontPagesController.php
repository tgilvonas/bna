<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontPagesController extends AbstractController
{
    protected ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        return $this->render('front-pages/homepage.html.twig', [
            'articles' => $this->articleRepository->getHomePageArticles(),
        ]);
    }

    #[Route('/kategorija/{slug}', name: 'category')]
    public function category(string $slug, Request $request, PaginatorInterface $paginator): Response
    {
        $categoryArticlesQuery = $this->articleRepository->getCategoryArticlesQuery($slug);

        $articles = $paginator->paginate($categoryArticlesQuery, $request->query->getInt('page', 1), 3);

        return $this->render('front-pages/category.html.twig', [
            'articles' => $articles,
            'showPagination' => true,
        ]);
    }

    #[Route('/kategorija/{category_slug}/{article_slug}', name: 'article')]
    public function article(string $article_slug): Response
    {
        return $this->render('front-pages/article.html.twig', [
            'article' => $this->articleRepository->getArticleBySlug($article_slug)
        ]);
    }

    #[Route('/paieska', name: 'search')]
    public function search(Request $request, PaginatorInterface $paginator)
    {
        $searchWord = $request->query->get('search');

        $searchByWordQuery = $this->articleRepository->getSearchByWordQuery($searchWord ?? '');

        $articles = $paginator->paginate($searchByWordQuery, $request->query->getInt('page', 1), 3);

        return $this->render('front-pages/search.html.twig', [
            'search_word' => $searchWord,
            'articles' => $articles,
            'showPagination' => true,
        ]);
    }
}
