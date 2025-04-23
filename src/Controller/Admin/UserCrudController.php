<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $redirectAction = Action::new('redirectToExternalPage')
            ->setLabel('Go to Website')
            ->setIcon('fa fa-external-link-alt')
            ->linkToUrl(function (User $user) {
                return 'https://127.0.0.1:8001/' . $user->getSlug();
            })
            ->setHtmlAttributes(['target' => '_blank']);


        return $actions
            ->add('index', $redirectAction)
            ->add('detail', $redirectAction);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('nom', 'Nom'),
            TextField::new('prenom', 'Prénom'),
            TextField::new('profession', 'Profession'),
            TextField::new('photo', 'Photo'),
            TextField::new('description', 'Description'),
            TextField::new('email'),
            TextField::new('telephone')->setRequired(false),
            TextareaField::new('linkdin')->setRequired(false),
            TextareaField::new('github')->setRequired(false),
            TextField::new('slug')->onlyOnIndex(),
        ];
    }
}
