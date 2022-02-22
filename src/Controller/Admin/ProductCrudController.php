<?php

namespace App\Controller\Admin;


use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

class ProductCrudController extends AbstractCrudController
{
    public const ACTION_DUPLICATE = "duplicate";
    public const PRODUCTS_BASE_PATH = 'uploads/images/products';
    public const PRODUCTS_UPLOAD_DIR = 'public/uploads/images/products';

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new(self::ACTION_DUPLICATE)
            ->linkToCrudAction('duplicateProduct')
            ->setCssClass('btn-info');
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, $duplicate)
            ->reorder(Crud::PAGE_EDIT,[self::ACTION_DUPLICATE, Action::SAVE_AND_RETURN]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            SlugField::new('slug')->setTargetFieldName('name')->setUnlockConfirmationMessage("Le Slug est généré automatiquement, mais il peut etre modifié"),
            TextField::new('name'),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR')->setStoredAsCents(false),

            ImageField::new('image')
                ->setBasePath(self::PRODUCTS_BASE_PATH)
                ->setUploadDir(self::PRODUCTS_UPLOAD_DIR)
                ->setSortable(false),
            AssociationField::new('category')->setQueryBuilder(function(QueryBuilder $queryBuilder){
                $queryBuilder->where('entity.active = true');
            }),
            BooleanField::new('active'),
            DateTimeField::new('updated_at')->hideOnForm(),
            DateTimeField::new('created_at')->hideOnForm(),

        ];
    }

    //Utilisé dans le Admin subscriber
   /* public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Product) return;
        $entityInstance->setCreatedAt(new DateTimeImmutable);

        //recupère le presist + flush du parent
        parent::persistEntity($entityManager, $entityInstance);
    }

   public function updateEntity(EntityManagerInterface $entityManager, $entityInstance):void{
        if (!$entityInstance instanceof Product) return;
        $entityInstance->setUpdatedAt(new DateTimeImmutable);

        //recupère le presist + flush du parent
        parent::updateEntity($entityManager, $entityInstance);
    }*/

    public function duplicateProduct(AdminContext $context,AdminUrlGenerator $adminUrlGenerator, entityManagerInterface $em ): response{

        /** @var Product $product */
        $products = $context->getEntity()->getInstance();

        $duplicateProduct = clone $products;

        parent::persistEntity($em, $duplicateProduct);

        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicateProduct->getId())
            ->generateUrl();

        return $this->redirect($url);
    }

}
