<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Translation\ExperienceProTranslationCrudController;
use App\Entity\ExperiencePro;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class ExperienceProCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return ExperiencePro::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('poste'),
            TextField::new('entreprise'),
            TextareaField::new('description')
            ->setRequired(false),
            IntegerField::new('dateDebut'),
            IntegerField::new('dateFin')->setRequired(false),
            AssociationField::new('user')
                ->autocomplete()
                ->setFormTypeOption('attr', ['data-search' => 'true'])
                ->setRequired(true),
        ];

        //permet de pouvoir ajouter si cest une page dedit ou une nouvelle exp
        if (Crud::PAGE_EDIT === $pageName || Crud::PAGE_NEW === $pageName) {
            $fields[] = CollectionField::new('translations')
                ->useEntryCrudForm(ExperienceProTranslationCrudController::class)
                ->setLabel('Traductions');
        }

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields([
                'id',
                'poste',
                'entreprise',
                'description',
                'dateDebut',
                'dateFin',
                'user.prenom',
                'user.nom',
            ])
            ->setPageTitle('index', 'Experiences Pro')
            ->setEntityLabelInSingular('Experience Pro')
            ->setEntityLabelInPlural('Experiences Pro');
    }
    
}
