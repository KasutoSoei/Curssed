<?php

namespace App\Controller;

use App\Form\ProductFormType;
use App\Form\UpdateProductFormType;
use App\Entity\Product;
use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{

    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }


    #[Route('/add', name: 'app_add_product')]
    public function addProduct(Request $request, EntityManagerInterface $entityManager): Response
    {

        $product = new Product();
        $productForm = $this->createForm(ProductFormType::class, $product);

        $productForm->handleRequest($request);

        if( $productForm->isSubmitted() && $productForm->isValid() ) {
            //$title = $product->getTitle();

            $product->setSeller($this->getUser());

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully added product');
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('product/add.html.twig', [
            'productForm' => $productForm->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'app_update_product')]
    public function updateProduct(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        // Fetch the product using the ID
        $product = $entityManager->getRepository(Product::class)->find($id);

        // Check if the product exists
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Create the form and bind it to the product
        $updateProductForm = $this->createForm(UpdateProductFormType::class, $product);

        // Handle the form submission
        $updateProductForm->handleRequest($request);

        if ($updateProductForm->isSubmitted() && $updateProductForm->isValid()) {
            // Persist the updated product to the database
            $entityManager->persist($product);
            $entityManager->flush();

            // Add a flash message and redirect (optional)
            $this->addFlash('success', 'Product updated successfully!');
            return $this->redirectToRoute('app_my_account'); // Adjust this route as needed
        }

        return $this->render('product/update.html.twig', [
            'updateProductForm' => $updateProductForm->createView(),
            'product' => $product, // Optional: pass product for additional info
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete_product')]
    public function deleteProduct(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('app_my_account');
    }

    #[Route('/buy/{id}', name: 'app_buy_product')]
    public function buyProduct(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        
        $product = $entityManager->getRepository(Product::class)->find($id);
        
        // Check if the product exists and isn't already sold
        if (!$product || $product->getStatus()) {
            throw $this->createNotFoundException('Product not found or already sold.');
        }

        $user = $this->getUser();
        $product->setStatus(true);
        $transaction = new Transaction();
        $transaction->setBuyer($user);
        $transaction->setSeller($product->getSeller());
        $transaction->setProduct($product);
        $transaction->setDate(new \DateTimeImmutable());
        // Update the product status to sold

        // Persist the changes
        $entityManager->persist($product);
        $entityManager->persist($transaction);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
