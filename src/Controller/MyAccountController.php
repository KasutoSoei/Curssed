<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyAccountController extends AbstractController
{
    #[Route('/myAccount', name: 'app_my_account')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        return $this->render('my_account/index.html.twig', [
            'user' => $user,
        ]);
    }
}
