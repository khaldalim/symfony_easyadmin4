<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository){

        return $this->render('home/index.html.twig', [
            'title' => "Page d'accueil",
            'products' => $productRepository->findBy(array('active' => true))
        ]);
    }
}
