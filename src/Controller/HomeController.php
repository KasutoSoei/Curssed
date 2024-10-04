<?php

namespace App\Controller;

use App\Entity\Product;

use App\Entity\User;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, ProductRepository $productRepository, Request $request): Response
    {
        $products = $entityManager->getRepository(Product::class)->findBy(['status' => false,]);
        $user = $this->getUser();
        
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'user' => $user,
        ]);
    }
}
