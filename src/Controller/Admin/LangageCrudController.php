<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Translation\LangageTransalationCrudController;
use App\Entity\Langage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class LangageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Langage::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('nomLangue'),
            TextField::new('niveau'),
            AssociationField::new('user')
            ->autocomplete()
            ->setFormTypeOption('attr', ['data-search' => 'true'])
            ->setRequired(true),

        ];

        //permet de pouvoir ajouter si cest une page dedit ou une nouvelle exp
        if (Crud::PAGE_EDIT === $pageName || Crud::PAGE_NEW === $pageName) {
            $fields[] = CollectionField::new('translations')
                ->useEntryCrudForm(LangageTransalationCrudController::class)
                ->setLabel('Traductions');
        }

        return $fields;
    }
    
    public function configureCrud(Crud $crud): Crud{
        return $crud
            ->setSearchFields([
                'id',
                'nomLangue',
                'niveau',
                'user.prenom',
                'user.nom',
            ]);
    }
}
