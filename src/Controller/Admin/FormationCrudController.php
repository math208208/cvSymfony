<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Translation\FormationTransalationCrudController;
use App\Entity\Formation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FormationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formation::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('intitule'),
            IntegerField::new('annee'),
            TextField::new('lieu'),
            TextField::new('imageFile')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
            ImageField::new('imageName', 'Image')
                ->setBasePath('/uploads/images')
                ->hideOnForm(),
            AssociationField::new('user')
            ->autocomplete()
            ->setFormTypeOption('attr', ['data-search' => 'true'])
            ->setRequired(true),
        ];

        if (Crud::PAGE_NEW === $pageName) {
            $fields[] = CollectionField::new('translations')
                ->useEntryCrudForm(FormationTransalationCrudController::class)
                ->setLabel('Traductions');
        }

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud{
        return $crud
            ->setSearchFields([
                'id',
                'intitule',
                'annee',
                'lieu',
                'user.prenom',
                'user.nom',
            ]);
    }
}
