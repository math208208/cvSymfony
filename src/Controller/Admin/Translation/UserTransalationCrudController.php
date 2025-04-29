<?php

namespace App\Controller\Admin\Translation;

use App\Entity\Translation\UserTranslation;
use App\Service\TranslationService as ServiceTranslationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class UserTransalationCrudController extends AbstractCrudController
{


    private ServiceTranslationService $translationService;
    private EntityManagerInterface $em;

    public function __construct(ServiceTranslationService $translationService, EntityManagerInterface $em)
    {
        $this->translationService = $translationService;
        $this->em = $em;
    }

    //Titre de la page 
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Traductions des personnes ')
            ->setSearchFields([
                'translatable.nom',
                'translatable.prenom',
                'profession',
                'description',
            ]);
    }

    public static function getEntityFqcn(): string
    {
        return UserTranslation::class;
    }



    public function configureFields(string $pageName): iterable
    {
        $user = $this->getUser();

        return [
            AssociationField::new('translatable')
                ->setFormTypeOption('choice_label', function ($entity) {
                    return $entity->getPrenom() . ' ' . $entity->getNom();
                })
                //condition pour recup seulement ce de la perssone connecté si sont role est user
                ->setFormTypeOption('query_builder', function (EntityRepository $er) use ($user) {
                    if ($this->isGranted('ROLE_ADMIN')) {
                        return $er->createQueryBuilder('t');
                    }

                    return $er->createQueryBuilder('t')
                        ->where('t.email = :email')
                        ->setParameter('email', $user->getUserIdentifier());
                }),

                
            ChoiceField::new('locale')
                ->setChoices([
                    'Français' => 'fr',
                    'English' => 'en',
                    'Español' => 'es'
                ]),
            TextField::new('profession'),
            TextareaField::new('description')
        ];
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
            $qb->join('entity.translatable', 't')
                ->andWhere('t.email = :email')
                ->setParameter('email', $user->getUserIdentifier());
        }

        return $qb;
    }
}
