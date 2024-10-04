<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;

use App\Entity\Product;

class ProductPageController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product_page')]
    public function index(EntityManagerInterface $em, ProductRepository $productRepository, string $id): Response
    {
        $product = $em->getRepository(Product::class)->find($id);

        return $this->render('product_page/productpage.html.twig', [
            'controller_name' => 'ProductPageController',
            'product' => $product
        ]);
    }
}
