<?php

namespace App\Controller\Admin;

use App\Entity\ExperiencePro;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ExperienceProCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExperiencePro::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('poste'),
            TextField::new('entreprise'),
            TextareaField::new('description'),
            IntegerField::new('dateDebut'),
            IntegerField::new('dateFin')->setRequired(false), 
            AssociationField::new('user')
            ->autocomplete()
            ->setFormTypeOption('attr', ['data-search' => 'true']),
        ];
    }


    public function configureCrud(Crud $crud): Crud{
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
            ]);
    }
}
