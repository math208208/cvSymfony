<?php

namespace App\Controller\Admin;

use App\Entity\Competence;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class CompetenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Competence::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $redirectAction = Action::new('redirectToExternalPage')
            ->setLabel('Go to Website')
            ->setIcon('fa fa-external-link-alt')
            ->linkToUrl(function (Competence $competence) {
                $user = $competence->getUser();
                return 'http://localhost:8001/' . $user->getSlug() . "/competences";
            })
            ->setHtmlAttributes(['target' => '_blank']);

        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(function (Competence $competence) {
                return 'http://localhost:8001/admin/competence/' . $competence->getId();
            });


        $actions = $actions
            ->add('index', $test)
            ->add('index', $redirectAction)
            ->add('detail', $redirectAction);


        return $actions;
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $user = $this->getUser();

        if (!$this->isGranted('ROLE_ADMIN') && $user !== null) {
            $qb->join('entity.user', 'u')
                ->andWhere('u.email = :email')
                ->setParameter('email', $user->getUserIdentifier());
        }

        return $qb;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            IntegerField::new('pourcentageMetrise'),
            AssociationField::new('user')
                ->autocomplete()
                ->setFormTypeOption('attr', ['data-search' => 'true'])
                ->setRequired(true),
            BooleanField::new('archived', 'Archivé')
                ->renderAsSwitch()
        ];
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields([
                'id',
                'nom',
                'pourcentageMetrise',
                'user.prenom',
                'user.nom',
            ]);
    }
}
