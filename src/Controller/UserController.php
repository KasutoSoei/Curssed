<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/myFavorites', name: 'app_user_favorites')]
    public function myFavorites(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $likedProducts = $user->getLikedProducts();

        return $this->render('user/productLike.html.twig', [
            'likedProducts' => $likedProducts,
        ]);
    }
}
