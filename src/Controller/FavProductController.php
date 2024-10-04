<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavProductController extends AbstractController
{
    #[Route('/favorite/{id}', name: 'app_fav_product', methods: ['POST'])]
    public function favoriteProduct(int $id, EntityManagerInterface $entityManager, Product $product): JsonResponse
    {
        $user = $this->getUser();  
        $addLikeProduct = $entityManager->getRepository(Product::class)->find($id);
        $seller = $addLikeProduct->getSeller();

        
        if ($user === $seller) {
            return new JsonResponse(['success' => false, 'message' => 'You cannot like your own product']);
        }

        if ($user->getLikedProducts()->contains($addLikeProduct)) {
            return new JsonResponse(['success' => false, 'message' => 'You have already liked this product']);
        }

        $user->addLikedProduct($addLikeProduct);
        $addLikeProduct->setFavorites($addLikeProduct->getFavorites() + 1);

        $message = new Message();
        $message->setSender($user);
        $message->setReceiver($seller);

        if ($user){
        $message->setContent(
            $user->getUsername() . " liked the product " . $addLikeProduct->getTitle() . "."
        );
        } else {
            $message->setContent(
                "User " . $user->getUsername() . " liked your product " . $addLikeProduct->getTitle() . "."
            );
        }

        $entityManager->persist($message);
        $entityManager->persist($user);
        $entityManager->persist($addLikeProduct);
        $entityManager->flush();
       

    

        return new JsonResponse([
            'success' => true,
            'message' => 'Product favorited successfully',
            'favorites' => $addLikeProduct->getFavorites(),
            'user' => $user,

        ]);
    }



    #[Route('/unfavorite/{id}', name: 'app_unfav_product', methods: ['POST'])]
public function unfavoriteProduct(int $id, EntityManagerInterface $entityManager): JsonResponse
{
    $user = $this->getUser();  
    $removeLikeProduct = $entityManager->getRepository(Product::class)->find($id);

    if (!$removeLikeProduct) {
        return new JsonResponse(['success' => false, 'message' => 'Product not found']);
    }

    if (!$user->getLikedProducts()->contains($removeLikeProduct)) {
        return new JsonResponse(['success' => false, 'message' => 'You have not liked this product yet']);
    }

    $user->removeLikedProduct($removeLikeProduct);
    $removeLikeProduct->setFavorites($removeLikeProduct->getFavorites() - 1);

    $entityManager->persist($user);
    $entityManager->persist($removeLikeProduct);
    $entityManager->flush();

    return new JsonResponse([
        'success' => true,
        'message' => 'Product unfavorited successfully',
        'favorites' => $removeLikeProduct->getFavorites(),
    ]);
}



    #[Route('/favoriteProducts', name:'app_display_fav_product', methods: ['GET'])]
    public function favoriteProducts(EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        $likedProducts = $user->getLikedProducts(); 

        return new JsonResponse([
            'likedProducts' => $likedProducts,
        ]);
    }
}
