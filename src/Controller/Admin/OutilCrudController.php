<?php

namespace App\Controller\Admin;

use App\Entity\Outil;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class OutilCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Outil::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('image'),
            AssociationField::new('user'),

        ];
    }
    
}
