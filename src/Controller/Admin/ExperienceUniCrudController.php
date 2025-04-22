<?php

namespace App\Controller\Admin;

use App\Entity\ExperienceUni;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


class ExperienceUniCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExperienceUni::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextField::new('sousTitre'),
            IntegerField::new('annee'),
            TextareaField::new('description'),
            AssociationField::new('user')
            ->autocomplete()
            ->setFormTypeOption('attr', ['data-search' => 'true']),
        ];
    }

    public function configureCrud(Crud $crud): Crud{
        return $crud
            ->setSearchFields([
                'id',
                'titre',
                'sousTitre',
                'annee',
                'description',
                'user.prenom',
                'user.nom',
            ]);
    }
}
