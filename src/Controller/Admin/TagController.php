<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    protected TagRepository $tagRepository;

    public function __construct(EntityManagerInterface $entityManager, TagRepository $tagRepository)
    {
        $this->entityManager = $entityManager;
        $this->tagRepository = $tagRepository;
    }

    #[Route('/admin/tag', name: 'admin_tag_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $paramsCountTotals = [
            'total' => true,
            'published' => true,
            'unpublished' => true,
        ];

        $tagQuery = $this->tagRepository->getTagQuery($paramsCountTotals);

        return $this->render('tag/index.html.twig', [
            'tags' => $paginator->paginate($tagQuery, $request->query->getInt('page', 1), 10),
        ]);
    }

    #[Route('/admin/tag/create', name: 'admin_tag_create')]
    public function create(Request $request): Response
    {
        $tag = new Tag();

        $form = $this->createForm(TagFormType::class, $tag, ['translation_domain' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_created');
            return $this->redirectToRoute('admin_tag_index');
        }

        return $this->render('/tag/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/tag/update/{id}', name: 'admin_tag_update')]
    public function update(Request $request, int $id): Response
    {
        $tag = $this->tagRepository->find($id);

        $form = $this->createForm(TagFormType::class, $tag, ['translation_domain' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_updated');
            return $this->redirectToRoute('admin_tag_index');
        }

        return $this->render('/tag/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/tag/delete/{id}', name: 'admin_tag_delete')]
    public function delete(int $id): Response
    {
        $tag = $this->tagRepository->find($id);

        $this->entityManager->remove($tag);
        $this->entityManager->flush();
        $this->addFlash('success', 'record_deleted');

        return $this->redirectToRoute('admin_tag_index');
    }
}
