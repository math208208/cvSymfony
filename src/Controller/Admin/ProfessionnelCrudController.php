<?php

namespace App\Controller\Admin;

use App\Entity\Outil;
use App\Entity\Professionnel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;

class ProfessionnelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Professionnel::class;
    }

    public function configureActions(Actions $actions): Actions
    {

        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(
                function (Professionnel $professionel) {
                    return 'http://localhost:8001/admin/professionel/' . $professionel->getId();
                }
            );


        $actions = $actions
            ->add('index', $test);


        return $actions;
    }




    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('metier'),
            TextField::new('entreprise'),
            TextField::new('email'),
        ];
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(
                [
                'id',
                'nom',
                'prenom',
                'metier',
                'entreprise',
                'email'
                ]
            );
    }
}
