<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Translation\ExperienceProTranslationCrudController;
use App\Entity\ExperiencePro;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

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
            ->linkToUrl(
                function (ExperiencePro $experiencePro) {
                    $user = $experiencePro->getUser();
                    return 'http://localhost:8001/' . $user->getSlug() . "/experiences";
                }
            )
            ->setHtmlAttributes(['target' => '_blank']);

        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(
                function (ExperiencePro $experiencePro) {
                    return 'http://localhost:8001/admin/experience-pro/' . $experiencePro->getId();
                }
            );


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
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('poste'),
            TextField::new('entreprise'),
            TextEditorField::new('description')
                ->setFormType(CKEditorType::class)
                ->setRequired(false),
            IntegerField::new('dateDebut'),
            IntegerField::new('dateFin')->setRequired(false),
            AssociationField::new('user')
                ->autocomplete()
                ->setFormTypeOption('attr', ['data-search' => 'true'])
                ->setRequired(true),
            BooleanField::new('archived', 'Archivé')
                ->renderAsSwitch()
        ];



        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(
                [
                'id',
                'poste',
                'entreprise',
                'description',
                'dateDebut',
                'dateFin',
                'user.prenom',
                'user.nom',
                ]
            )
            ->setPageTitle('index', 'Experiences Pro')
            ->setEntityLabelInSingular('Experience Pro')
            ->setEntityLabelInPlural('Experiences Pro')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
}
