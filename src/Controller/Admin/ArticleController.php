<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    protected ArticleRepository $articleRepository;

    protected CategoryRepository $categoryRepository;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/article', name: 'admin_article_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $searchParams = $request->query->all();

        $articlesQuery = $this->articleRepository->getArticlesQuery($searchParams);

        $articles = $paginator->paginate($articlesQuery, $request->query->getInt('page', 1), 10);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'searchParams' => $searchParams,
            'categories' => $this->categoryRepository->getCategoriesQuery()->getResult(),
        ]);
    }

    #[Route('/admin/article/create', name: 'admin_article_create')]
    public function create(Request $request, SluggerInterface $slugger): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleFormType::class, $article, ['translation_domain' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();

            $articleImg = $form->get('img_original')->getData();

            if ($articleImg) {
                $originalFilename = pathinfo($articleImg->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $articleImg->guessExtension();
                try {
                    $articleImg->move($this->getParameter('articles_img_directory'), $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'file_upload_error_occurred');
                }
                $article->setImgOriginal($newFilename);
            }

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->addFlash('success', 'record_created');

            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('/article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/admin/article/update/{id}', name: 'admin_article_update')]
    public function update(Request $request, int $id, SluggerInterface $slugger): Response
    {
        $article = $this->articleRepository->find($id);

        $oldArticleImg = $article->getImgOriginal();

        $form = $this->createForm(ArticleFormType::class, $article, ['translation_domain' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();

            $articleImg = $form->get('img_original')->getData();

            if ($articleImg) {
                $originalFilename = pathinfo($articleImg->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $articleImg->guessExtension();
                try {
                    $articleImgDirectory = $this->getParameter('articles_img_directory');
                    $articleImg->move($articleImgDirectory, $newFilename);
                    if (file_exists($articleImgDirectory . '/' . $oldArticleImg) && !empty($newFilename)) {
                        unlink($articleImgDirectory . '/' . $oldArticleImg);
                    }
                    $this->addFlash('success', 'files_changed');
                } catch (FileException $e) {
                    $this->addFlash('error', 'file_upload_error_occurred');
                }

                if (!empty($newFilename)) {
                    $article->setImgOriginal($newFilename);
                }
            } else {
                $article->setImgOriginal($oldArticleImg);
            }

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->addFlash('success', 'record_updated');

            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('/article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/admin/article/delete/{id}', name: 'admin_article_delete')]
    public function delete(int $id)
    {
        $article = $this->articleRepository->find($id);

        $articleImgDirectory = $this->getParameter('articles_img_directory');
        if (file_exists($articleImgDirectory . '/' . $article->getImgOriginal()) && !empty($article->getImgOriginal())) {
            unlink($articleImgDirectory . '/' . $article->getImgOriginal());
        }

        $this->entityManager->remove($article);
        $this->entityManager->flush();

        $this->addFlash('success', 'record_deleted');
        $this->addFlash('success', 'files_deleted');

        return $this->redirectToRoute('admin_article_index');
    }
}
