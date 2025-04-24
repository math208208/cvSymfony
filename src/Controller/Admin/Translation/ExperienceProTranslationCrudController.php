<?php

namespace App\Controller\Admin\Translation;


use App\Entity\Translation\ExperienceProTranslation as TranslationExperienceProTranslation;
use App\Service\TranslationService as ServiceTranslationService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ExperienceProTranslationCrudController extends AbstractCrudController
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
            ->setPageTitle(Crud::PAGE_INDEX, 'Traductions des expériences professionnelles');
    }

    public static function getEntityFqcn(): string
    {
        return TranslationExperienceProTranslation::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('translatable')
            ->setFormTypeOption('choice_label', 'poste') ,
            ChoiceField::new('locale')
                ->setChoices([
                    'Français' => 'fr',
                    'English' => 'en',
                    'Español' => 'es'
                ]),
            TextField::new('poste'),
            TextareaField::new('description')
        ];
    }

    
    

  
}
