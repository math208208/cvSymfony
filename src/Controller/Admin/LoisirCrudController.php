<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Translation\LoisirTransalationCrudController;
use App\Entity\Loisir;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoisirCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Loisir::class;
    }

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    public function configureActions(Actions $actions): Actions
    {
        $redirectAction = Action::new('redirectToExternalPage')
            ->setLabel('Go to Website')
            ->setIcon('fa fa-external-link-alt')
            ->linkToUrl(
                function (Loisir $loisir) {
                    $user = $loisir->getUser();
                    return 'http://localhost:8001/' . $user->getSlug();
                }
            )
            ->setHtmlAttributes(['target' => '_blank']);

        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(
                function (Loisir $loisir) {
                    return 'http://localhost:8001/admin/loisir/' . $loisir->getId();
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
            TextField::new('nom'),     // Nom du loisir sera affiché dans le formulaire et la liste
            TextField::new('imageFile')     // Champ pour télécharger l'image du loisir
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),                //visible uniquement dans le formulaire
            ImageField::new('imageName', 'Image') // Champ pour afficher l'image du loisir
                ->setBasePath('/uploads/images')
                ->hideOnForm(),         //visible uniquement dans la liste
            AssociationField::new('user')      // Association avec l'entité User
                ->autocomplete()            // Permet l'autocomplétion pour la sélection de l'utilisateur
                ->setFormTypeOption('attr', ['data-search' => 'true'])
                ->setRequired(true),    // Champ obligatoire

            BooleanField::new('archived', 'Archivé')
                ->renderAsSwitch()

        ];
        return $fields;
    }

    public function configureCrud(Crud $crud): Crud  //gestion des champs qui sont recherchables dans la liste
    {
        return $crud
            ->setSearchFields(
                [
                'id',
                'nom',
                'user.prenom',
                'user.nom',
                ]
            );
    }


    public function archiveLoisir(AdminContext $context): RedirectResponse
    {
        $entityId = $context->getRequest()->query->get('entityId');

        $loisir = $this->entityManager->getRepository(Loisir::class)->find($entityId);


        $loisir->setArchived(true);
        $this->entityManager->flush();

        $this->addFlash('success', 'Loisir archivé avec succès.');

        $url = $context->getReferrer() ?? $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }
}
