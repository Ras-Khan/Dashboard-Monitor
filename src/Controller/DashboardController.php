<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\JsonProductRepository;

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
}