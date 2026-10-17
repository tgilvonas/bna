<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    protected CategoryRepository $categoryRepository;

    protected EntityManagerInterface $entityManager;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/category', name: 'admin_category_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $paramsCountTotals = [
            'total' => true,
            'published' => true,
            'unpublished' => true,
        ];

        $categoriesQuery = $this->categoryRepository->getCategoriesQuery($paramsCountTotals);

        $categories = $paginator->paginate($categoriesQuery, $request->query->getInt('page', 1), 10);

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/category/create', name: 'admin_category_create')]
    public function create(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category, ['translation_domain' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_created');
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/category/update/{id}', name: 'admin_category_update')]
    public function update(Request $request, int $id)
    {
        $category = $this->categoryRepository->find($id);

        $form = $this->createForm(CategoryFormType::class, $category, ['translation_domain' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_updated');
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/category/delete/{id}', name: 'admin_category_delete')]
    public function delete(int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        $this->entityManager->remove($category);
        $this->entityManager->flush();
        $this->addFlash('success', 'record_deleted');

        return $this->redirectToRoute('admin_category_index');
    }
}
