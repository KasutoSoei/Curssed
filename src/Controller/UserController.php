<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Product;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user')]
    public function index(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $products = $entityManager->getRepository(Product::class)->findBy([
            'status' => false,
            'seller' => $id,
        ]);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'products' => $products,
        ]);
    }
}