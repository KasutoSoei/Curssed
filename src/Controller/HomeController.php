<?php

namespace App\Controller;

use App\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function listAllProducts(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findBy(['status' => false,]);
        //dd($products);

        return $this->render('home/index.html.twig', [
            'products' => $products,
        ]);
    }
}
