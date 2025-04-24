<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Translation\FormationTransalationCrudController;
use App\Controller\Admin\Translation\LoisirTransalationCrudController;
use App\Entity\Loisir;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class LoisirCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Loisir::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
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

        //permet de pouvoir ajouter si cest une page dedit ou une nouvelle exp
        if (Crud::PAGE_EDIT === $pageName || Crud::PAGE_NEW === $pageName) {
            $fields[] = CollectionField::new('translations')
                ->useEntryCrudForm(LoisirTransalationCrudController::class)
                ->setLabel('Traductions');
        }

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields([
                'id',
                'nom',
                'user.prenom',
                'user.nom',
            ]);
    }
}
