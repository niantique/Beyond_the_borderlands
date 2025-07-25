<?php

namespace App\Controller\Admin;

use App\Entity\Stop;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StopCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Stop::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('content'),
            DateField::new('date'),
            TextField::new('location'),
            TextField::new('image')->setLabel('Image URL')->hideOnIndex(),
            ImageField::new('image')->setBasePath('')->onlyOnIndex(),
            AssociationField::new('trip')
        ];
    }
    
}
