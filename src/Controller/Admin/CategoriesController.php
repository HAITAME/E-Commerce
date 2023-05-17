<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CategoriesFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categories', name: 'admin_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findBy([], ['categoryOrder' => 'asc']);

        return $this->render('admin/categories/index.html.twig', compact('categories'));
    }
    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
{
    $categorie = new Categories();
    $categorieForm = $this->createForm(CategoriesFormType::class, $categorie);
    $categorieForm->handleRequest($request);

    if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
        $name = $categorie->getName();
        $slug = $slugger->slug($name)->lower();

        $categorie->setSlug($slug);

        $lastCategory = $em->getRepository(Categories::class)->findOneBy([], ['categoryOrder' => 'DESC']);

        if ($lastCategory) {
            $lastCategoryOrder = $lastCategory->getCategoryOrder();
            $categorie->setCategoryOrder($lastCategoryOrder + 1);
        } else {
            $categorie->setCategoryOrder(1); // If no existing categories, set category_order as 1
        }

        $em->persist($categorie);
        $em->flush();

        $this->addFlash('success', 'Categorie ajoutée avec succès');
        return $this->redirectToRoute('admin_categories_index');
    }

    return $this->render('admin/categories/add.html.twig', [
        'categorieForm' => $categorieForm->createView(),
    ]);
}
}