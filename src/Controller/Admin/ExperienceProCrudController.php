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
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
class ExperienceProCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return ExperiencePro::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $redirectAction = Action::new('redirectToExternalPage')
            ->setLabel('Go to Website')
            ->setIcon('fa fa-external-link-alt')
            ->linkToUrl(function (ExperiencePro $experiencePro) {
                $user = $experiencePro->getUser();
                return 'https://127.0.0.1:8001/' . $user->getSlug()."/experiences";
            })
            ->setHtmlAttributes(['target' => '_blank']);

        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(function (ExperiencePro $experiencePro) {
                return 'https://127.0.0.1:8001/admin/experience-pro/' . $experiencePro->getId();
            });


        return $actions
            ->add('index', $test)
            ->add('index', $redirectAction)
            ->add('detail', $redirectAction);
            
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
        if (Crud::PAGE_NEW === $pageName) {
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
