<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $productRepository->findBy(array('active' => true))
        ]);
    }


    #[Route('/product/{slug}', name: 'product-single')]
    public function indexSingle(Product $product): Response
    {
        return $this->render('product/single.html.twig', [
            'controller_name' => 'Single',
            'product' => $product
        ]);
    }
}
