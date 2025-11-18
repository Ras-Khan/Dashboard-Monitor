<?php

namespace App\Controller;

use App\Model\Product;
use App\Service\JsonProductRepository;
use App\Form\ProductType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(JsonProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('dashboard/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/add', name: 'app_product_add')]
    public function add(Request $request, JsonProductRepository $productRepository): Response
    {
        // 1. Create a Product object with initial dummy values for the form (ID is null)
        $product = new Product(id: null, name: '', url: null, currentPrice: 0.00, lastUpdated: new DateTimeImmutable());

        // 2. Create the form, passing the Product object
        $form = $this->createForm(ProductType::class, $product);

        // 3. Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $newProduct */
            $newProduct = $form->getData();
            
            // 4. Save the new product to the JSON file
            $productRepository->save($newProduct);

            $this->addFlash('success', 'Product added successfully!');
            return $this->redirectToRoute('app_dashboard');
        }

        // 5. Render the form
        return $this->render('dashboard/add.html.twig', [
            'productForm' => $form->createView(),
        ]);
    }
}