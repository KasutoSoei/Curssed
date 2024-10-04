<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductsFilterFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get all products that are not sold
        $user = $this->getUser();

        // Create the category filter form
        $productCategoryFilterForm = $this->createForm(ProductsFilterFormType::class);
        $productCategoryFilterForm->handleRequest($request);

        // Handle the form submission for filtering products by category
        if ($productCategoryFilterForm->isSubmitted() && $productCategoryFilterForm->isValid()) {
            $selectedCategory = $productCategoryFilterForm->get('category')->getData();

            if ($selectedCategory)
            {
                return $this->redirectToRoute('app_home_filtered_search', [
                    "categoryId" => $selectedCategory->getId()
                ]);
            } else {
                return $this->redirectToRoute(route: 'app_home');
            }
        }

        $products = $entityManager->getRepository(Product::class)->findBy(['status' => false]);

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'user' => $user,
            'productCategoryFilterForm' => $productCategoryFilterForm->createView(),
        ]);
    }

    #[Route('/home/{categoryId}', name: 'app_home_filtered_search')]
    public function filteredSearch(Request $request, EntityManagerInterface $entityManager, int $categoryId, UserInterface $user): Response
    {
        $productCategoryFilterForm = $this->createForm(ProductsFilterFormType::class);
        $productCategoryFilterForm->handleRequest($request);

        // Handle the form submission for filtering products by category
        if ($productCategoryFilterForm->isSubmitted() && $productCategoryFilterForm->isValid()) {
            $selectedCategory = $productCategoryFilterForm->get('category')->getData();

            if ($selectedCategory)
            {
                return $this->redirectToRoute('app_home_filtered_search', [
                    "categoryId" => $selectedCategory->getId()
                ]);
            } else {
                return $this->redirectToRoute(route: 'app_home');
            }
        }
        
        $products = $entityManager->getRepository(Product::class)->findBy([
            'status' => false, // Filter unsold products
            'category' => $categoryId, // Filter by selected category
        ]);
        
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'user' => $user,
            'productCategoryFilterForm' => $productCategoryFilterForm->createView(),
        ]);
    }
}
