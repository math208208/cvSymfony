<?php

namespace App\Controller\Admin\Translation;

use App\Entity\Translation\LangageTranslation;
use App\Service\TranslationService as ServiceTranslationService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LangageTransalationCrudController extends AbstractCrudController
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
            ->setPageTitle(Crud::PAGE_INDEX, 'Traductions des langues ')
            ->setSearchFields([
                'translatable.nomLangue', 
                'translatable.user.nom', 
                'translatable.user.prenom', 
                'nomLangue',
            ]);
    }

    public static function getEntityFqcn(): string
    {
        return LangageTranslation::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('translatable')
            ->setFormTypeOption('choice_label', function ($entity) {
                return $entity->getUser()->getPrenom() . ' ' . $entity->getUser()->getNom() . ' -> ' . $entity->getNomLangue();
            }),
            ChoiceField::new('locale')
                ->setChoices([
                    'Français' => 'fr',
                    'English' => 'en',
                    'Español' => 'es'
                ]),
            TextField::new('nomLangue'),
        ];
    }

    
    

  
}
