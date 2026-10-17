<?php

namespace App\Controller\Admin;

use App\Entity\Banner;
use App\Form\BannerFormType;
use App\Repository\BannerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BannerController extends AbstractController
{
    protected BannerRepository $bannerRepository;

    protected EntityManagerInterface $entityManager;

    public function __construct(BannerRepository $bannerRepository, EntityManagerInterface $entityManager)
    {
        $this->bannerRepository = $bannerRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/banner', name: 'admin_banner_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $bannersQuery = $this->bannerRepository->getBannersQuery();

        $banners = $paginator->paginate($bannersQuery, $request->query->getInt('page', 1), 10);

        return $this->render('banner/index.html.twig', [
            'banners' => $banners,
        ]);
    }

    #[Route('/admin/banner/create', name: 'admin_banner_create')]
    public function create(Request $request, SluggerInterface $slugger): Response
    {
        $banner = new Banner();

        $form = $this->createForm(BannerFormType::class, $banner, ['translation_domain' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $banner = $form->getData();

            $bannerImg = $form->get('img')->getData();

            if ($bannerImg) {
                $originalFilename = pathinfo($bannerImg->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $bannerImg->guessExtension();
                try {
                    $bannerImg->move($this->getParameter('banners_directory'), $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'file_upload_error_occurred');
                }
                $banner->setImg($newFilename);
            }

            $this->entityManager->persist($banner);
            $this->entityManager->flush();

            $this->addFlash('success', 'record_created');

            return $this->redirectToRoute('admin_banner_index');
        }

        return $this->render('/banner/edit.html.twig', [
            'form' => $form->createView(),
            'banner' => $banner,
        ]);
    }

    #[Route('/admin/banner/update/{id}', name: 'admin_banner_update')]
    public function update(Request $request, int $id, SluggerInterface $slugger): Response
    {
        $banner = $this->bannerRepository->find($id);

        $oldBannerImg = $banner->getImg();

        $form = $this->createForm(BannerFormType::class, $banner, ['translation_domain' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $banner = $form->getData();

            $bannerImg = $form->get('img')->getData();

            if ($bannerImg) {
                $originalFilename = pathinfo($bannerImg->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $bannerImg->guessExtension();
                try {
                    $bannersDirectory = $this->getParameter('banners_directory');
                    $bannerImg->move($bannersDirectory, $newFilename);
                    if (file_exists($bannersDirectory . '/' . $oldBannerImg) && !empty($newFilename)) {
                        unlink($bannersDirectory . '/' . $oldBannerImg);
                    }
                    $this->addFlash('success', 'files_changed');
                } catch (FileException $e) {
                    $this->addFlash('error', 'file_upload_error_occurred');
                }

                if (!empty($newFilename)) {
                    $banner->setImg($newFilename);
                }
            } else {
                $banner->setImg($oldBannerImg);
            }

            $this->entityManager->persist($banner);
            $this->entityManager->flush();

            $this->addFlash('success', 'record_updated');

            return $this->redirectToRoute('admin_banner_index');
        }

        return $this->render('/banner/edit.html.twig', [
            'form' => $form->createView(),
            'banner' => $banner,
        ]);
    }

    #[Route('/admin/banner/delete/{id}', name: 'admin_banner_delete')]
    public function delete(int $id): Response
    {
        $banner = $this->bannerRepository->find($id);

        $bannersDirectory = $this->getParameter('banners_directory');
        if (file_exists($bannersDirectory . '/' . $banner->getImg()) && !empty($banner->getImg())) {
            unlink($bannersDirectory . '/' . $banner->getImg());
        }

        $this->entityManager->remove($banner);
        $this->entityManager->flush();

        $this->addFlash('success', 'record_deleted');
        $this->addFlash('success', 'files_deleted');

        return $this->redirectToRoute('admin_banner_index');
    }
}
