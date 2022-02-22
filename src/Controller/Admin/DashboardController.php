<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ProductCrudController::class)
            ->generateUrl();


        return $this->redirect($url);


    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony 6 + Easyadmin4');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute("Home", "fas fa-home", "home");
        yield MenuItem::section('Products');

        yield MenuItem::submenu('Actions', "fas fa-bars")->setSubItems([
                MenuItem::linkToCrud('Create product', "fas fa-plus", Product::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Show product', "fas fa-eye", Product::class),
        ]);

        yield MenuItem::section('Categories');
        yield MenuItem::submenu('Actions', "fas fa-bars")->setSubItems([
            MenuItem::linkToCrud('Create category', "fas fa-plus", Category::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show category', "fas fa-eye", Category::class),
        ]);


        yield MenuItem::section('Users');
        yield MenuItem::submenu('Actions', "fas fa-bars")->setSubItems([
            MenuItem::linkToCrud('Create user', "fas fa-plus", User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show user', "fas fa-eye", User::class),
        ]);

    }
}
