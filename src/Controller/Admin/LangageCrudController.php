<?php

namespace App\Controller\Admin;

use App\Entity\Langage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class LangageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Langage::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nomLangue'),
            TextField::new('niveau'),
            AssociationField::new('user'),

        ];
    }
    
}
