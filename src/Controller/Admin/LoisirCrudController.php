<?php

namespace App\Controller\Admin;

use App\Entity\Loisir;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class LoisirCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Loisir::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('photo'),
            AssociationField::new('user'),
        ];
    }
}
