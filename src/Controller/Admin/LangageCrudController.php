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
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
class LangageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Langage::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $redirectAction = Action::new('redirectToExternalPage')
            ->setLabel('Go to Website')
            ->setIcon('fa fa-external-link-alt')
            ->linkToUrl(function (Langage $langage) {
                $user = $langage->getUser();
                return 'https://127.0.0.1:8001/' . $user->getSlug()."/competences";
            })
            ->setHtmlAttributes(['target' => '_blank']);

        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(function (Langage $langage) {
                return 'https://127.0.0.1:8001/admin/langage/' . $langage->getId();
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
            TextField::new('nomLangue'),
            TextField::new('niveau'),
            AssociationField::new('user')
            ->autocomplete()
            ->setFormTypeOption('attr', ['data-search' => 'true'])
            ->setRequired(true),

        ];

        if (Crud::PAGE_NEW === $pageName) {
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
