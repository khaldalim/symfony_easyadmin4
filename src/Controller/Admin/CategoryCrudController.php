<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            BooleanField::new('active'),
            DateTimeField::new('updated_at')->hideOnForm(),
            DateTimeField::new('created_at')->hideOnForm(),
        ];
    }

    //Utilisé dans le Admin subscriber
   /* public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
       if (!$entityInstance instanceof Category)return;
       $entityInstance->setCreatedAt(new DateTimeImmutable);

       //recupère le presist + flush du parent
       parent::persistEntity($entityManager, $entityInstance);
    }*/


    public function deleteEntity(EntityManagerInterface $em, $entityInstance):void{
        if (!$entityInstance instanceof Category) return;
        foreach ($entityInstance->getProducts() as $product){
            $em->remove($product);
        }
        parent::deleteEntity($em, $entityInstance);
    }

}
