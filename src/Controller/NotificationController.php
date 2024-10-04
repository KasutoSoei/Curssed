<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function notification(Product $product): Response
    {
        $user = $this->getUser();

        $seller = $product->getSeller();

        return $this->render('notification/index.html.twig', [
            
        ]);
    }
}
