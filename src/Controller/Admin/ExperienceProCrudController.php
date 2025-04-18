<?php

namespace App\Controller\Admin;

use App\Entity\ExperiencePro;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

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
            IntegerField::new('dateFin')->hideWhenCreating(), // facultatif si tu veux pas forcer dès la création
            AssociationField::new('user'),
        ];
    }
}
