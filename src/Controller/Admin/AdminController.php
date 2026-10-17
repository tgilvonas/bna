<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Form\AdminFormType;
use App\Form\AdminProfileFormType;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AdminController extends AbstractController
{
    protected AdminRepository $adminRepository;

    protected EntityManagerInterface $entityManager;

    protected UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        AdminRepository $adminRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    )
    {
        $this->adminRepository = $adminRepository;
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/admin/admins', name: 'admin_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $adminsQuery = $this->adminRepository->getAdminsQuery();

        $admins = $paginator->paginate($adminsQuery, $request->query->getInt('page', 1), 10);

        return $this->render('admin/index.html.twig', [
            'admins' => $admins,
        ]);
    }

    #[Route('/admin/admins/create', name: 'admin_create')]
    public function create(Request $request): Response
    {
        $admin = new Admin();

        $form = $this->createForm(AdminFormType::class, $admin, ['translation_domain' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $form->getData();
            $admin->setPassword($this->userPasswordHasher->hashPassword($admin, $form->get('password')->getData()));
            $this->entityManager->persist($admin);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_created');
            return $this->redirectToRoute('admin_index');
        }

        return $this->render('/admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/admins/update/{id}', name: 'admin_update')]
    public function update(Request $request, int $id): Response
    {
        $admin = $this->adminRepository->find($id);

        $form = $this->createForm(AdminFormType::class, $admin, ['translation_domain' => 'admin']);

        $oldPassword = $admin->getPassword();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $form->getData();
            if (empty($admin->getPassword())) {
                $admin->setPassword($oldPassword);
            } else {
                $admin->setPassword($this->userPasswordHasher->hashPassword($admin, $form->get('password')->getData()));
            }
            $this->entityManager->persist($admin);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_updated');
            return $this->redirectToRoute('admin_index');
        }

        return $this->render('/admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/admins/delete/{id}', name: 'admin_delete')]
    public function delete(int $id): Response
    {
        $admin = $this->adminRepository->find($id);

        $this->entityManager->remove($admin);
        $this->entityManager->flush();
        $this->addFlash('success', 'record_deleted');

        return $this->redirectToRoute('admin_index');
    }

    #[Route('/admin/profile', name: 'admin_profile')]
    public function profile(Request $request, Security $security): Response
    {
        $admin = $security->getUser();

        $oldPassword = $admin->getPassword();
        $roles = $admin->getRoles();

        $form = $this->createForm(AdminProfileFormType::class, $admin, ['translation_domain' => 'admin']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $form->getData();

            if (empty($admin->getPassword())) {
                $admin->setPassword($oldPassword);
            } else {
                $admin->setPassword($this->userPasswordHasher->hashPassword($admin, $form->get('password')->getData()));
            }

            $admin->setRoles($roles);

            $this->entityManager->persist($admin);
            $this->entityManager->flush();

            $this->addFlash('success', 'profile_updated');
            return $this->redirectToRoute('admin_profile');
        }

        return $this->render('admin/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
