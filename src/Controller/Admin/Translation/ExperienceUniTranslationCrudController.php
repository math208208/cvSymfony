<?php

namespace App\Controller\Admin\Translation;


use App\Entity\ExperienceUni;
use App\Entity\Translation\Translation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ExperienceUniTranslationCrudController extends AbstractCrudController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getEntityFqcn(): string
    {
        return Translation::class;
    }

    //affiche seulement les objet ayant ExperienceUni en entité
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {


        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $admin = $this->getUser();

        if (!$this->isGranted('ROLE_ADMIN') && $admin !== null) {

            if ($admin instanceof \App\Entity\Admin) {
                $adminEmail = $admin->getEmail();
            } else {
                throw new \Exception('Utilisateur non valide.');
            }


            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $adminEmail]);

            if ($user) {
                $fullName = $user->__toString();

                $qb->andWhere('entity.entity = :ExperienceUni')
                    ->setParameter('ExperienceUni', \App\Entity\ExperienceUni::class)
                    ->andWhere('entity.personne = :fullName')
                    ->setParameter('fullName', $fullName);
            }
        } else {
            $qb->andWhere('entity.entity = :ExperienceUni')
                ->setParameter('ExperienceUni', \App\Entity\ExperienceUni::class);
        }


        return $qb;
    }


    public function configureActions(Actions $actions): Actions
    {
        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(function (Translation $translation) {
                return 'http://localhost:8001/admin/experience-uni-translation/' . $translation->getId();
            });


        $actions = $actions
            ->add('index', $test);

        return $actions;
    }


    public function configureFields(string $pageName): iterable
    {

        return [
            ChoiceField::new('entity')
                ->setLabel('Entité')
                ->setChoices([
                    'ExperienceUni' => ExperienceUni::class,
                ])
                ->setFormTypeOption('data', ExperienceUni::class)
                ->setRequired(true),


            TextField::new('personne')
                ->setLabel('Personne')
                ->hideOnForm(),


            ChoiceField::new('entityId')
                ->setLabel('ID de ExperienceUni')
                ->setChoices($this->getExperienceUniIds())
                ->onlyOnForms(),

            ChoiceField::new('attribute')
                ->setLabel('Attribut')
                ->setChoices($this->getExperienceUniAttributs()),

            TextareaField::new('fr')
                ->setLabel('Francais')
                ->hideOnForm(),



            TextEditorField::new('en', 'Anglais')
                ->setFormType(CKEditorType::class),

            TextEditorField::new('es', 'Espagnol')
                ->setFormType(CKEditorType::class),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    private function getExperienceUniIds(): array
    {
        $attributes = array_values($this->getExperienceUniAttributs());

        $choices = [];
        if (!$this->isGranted('ROLE_ADMIN')) {
            $admin = $this->getUser();

            if (!$admin instanceof \App\Entity\Admin) {
                throw new \Exception('Utilisateur non valide.');
            }

            $adminEmail = $admin->getEmail();
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $adminEmail]);

            if (!$user) {
                throw new \Exception('Aucun utilisateur associé à cet administrateur.');
            }

            $experiencesUni = $this->em->getRepository(ExperienceUni::class)->findBy(['user' => $user]);

            foreach ($experiencesUni as $experienceUni) {

                if ($this->hasMissingTranslations($experienceUni, $attributes)) {
                    $choices[$experienceUni->getUser() . " -> " . $experienceUni->getTitre()] = $experienceUni->getId();
                }
            }
        } else {
            $experiencesUni = $this->em->getRepository(ExperienceUni::class)->findAll();

            foreach ($experiencesUni as $experienceUni) {

                if ($this->hasMissingTranslations($experienceUni, $attributes)) {
                    $choices[$experienceUni->getUser() . " -> " . $experienceUni->getTitre()."-> id :".$experienceUni->getId()] = $experienceUni->getId();
                }
            }
        }
        return $choices;
    }

    private function hasMissingTranslations(ExperienceUni $experienceUni, array $attributes): bool
    {
        foreach ($attributes as $attribute) {
            $existing = $this->em->getRepository(Translation::class)->findOneBy([
                'entity' => ExperienceUni::class,
                'entityId' => $experienceUni->getId(),
                'attribute' => $attribute,
            ]);

            if (!$existing) {
                return true;
            }
        }

        return false;
    }

    private function getExperienceUniAttributs(): array
    {
        return [
            'Titre' => 'titre',
            'Sous Titre' => 'sousTitre',
            'Description' => 'description',
        ];
    }




    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Translation) {
            return;
        }

        if (
            $entityInstance->getEntity() === ExperienceUni::class &&
            $entityInstance->getEntityId()
        ) {
            $experienceUni = $this->em->getRepository(ExperienceUni::class)->find($entityInstance->getEntityId());

            if ($experienceUni) {
                $attribute = $entityInstance->getAttribute();
                $getter = 'get' . ucfirst($attribute);


                if (method_exists($experienceUni, $getter)) {
                    $value = $experienceUni->$getter();

                    $existingTranslation = $this->em->getRepository(Translation::class)->findOneBy([
                        'entity' => ExperienceUni::class,
                        'entityId' => $experienceUni->getId(),
                        'attribute' => $attribute,
                    ]);

                    if ($existingTranslation) {
                        $existingTranslation->setEn($entityInstance->getEn());
                        $existingTranslation->setEs($entityInstance->getEs());

                        if (empty($existingTranslation->getFr())) {
                            $existingTranslation->setFr($value);
                        }

                        $existingTranslation->setPersonne($experienceUni->getUser());

                        $entityManager->persist($existingTranslation);
                        $entityManager->flush();

                        return;
                    } else {
                        $entityInstance->setFr($value);
                    }
                }

                $entityInstance->setPersonne($experienceUni->getUser());
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
