<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavProductController extends AbstractController
{
    #[Route('/favorite/{id}', name: 'app_fav_product', methods: ['POST'])]
    public function favoriteProduct(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return new JsonResponse(['success' => false, 'message' =>'Products no\'t found']);
        }

        $product->setFavorites($product->getFavorites() + 1);
        $entityManager->flush();


        return new JsonResponse([
            'success' => true,
            'message' => 'Product favorited successfully',
            'favorites' => $product->getFavorites(),
        ]);
    }

    #[Route('/', name:'app_display_fav_product', methods: ['GET'])]
    public function favoriteProducts(int $id, EntityManagerInterface $entityManager): void
    {
    }
}
