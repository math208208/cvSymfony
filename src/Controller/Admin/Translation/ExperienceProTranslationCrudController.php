<?php

namespace App\Controller\Admin\Translation;


use App\Entity\ExperiencePro;
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

class ExperienceProTranslationCrudController extends AbstractCrudController
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

    //affiche seulement les objet ayant ExperiencePro en entité
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

                $qb->andWhere('entity.entity = :ExperiencePro')
                    ->setParameter('ExperiencePro', \App\Entity\ExperiencePro::class)
                    ->andWhere('entity.personne = :fullName')
                    ->setParameter('fullName', $fullName);
            }
        } else {
            $qb->andWhere('entity.entity = :ExperiencePro')
                ->setParameter('ExperiencePro', \App\Entity\ExperiencePro::class);
        }


        return $qb;
    }


    public function configureActions(Actions $actions): Actions
    {
        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(function (Translation $translation) {
                return 'http://localhost:8001/admin/experience-pro-translation/' . $translation->getId();
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
                    'ExperiencePro' => ExperiencePro::class,
                ])
                ->setFormTypeOption('data', ExperiencePro::class)
                ->setRequired(true),


            TextField::new('personne')
                ->setLabel('Personne')
                ->hideOnForm(),

            ChoiceField::new('entityId')
                ->setLabel('ID de ExperiencePro')
                ->setChoices($this->getExperienceProIds())
                ->onlyOnForms(),

            ChoiceField::new('attribute')
                ->setLabel('Attribut')
                ->setChoices($this->getExperienceProAttributs()),

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


    private function getExperienceProIds(): array
    {
        $attributes = array_values($this->getExperienceProAttributs());

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

            $experiencesPro = $this->em->getRepository(ExperiencePro::class)->findBy(['user' => $user]);

            foreach ($experiencesPro as $experiencePro) {
                if ($this->hasMissingTranslations($experiencePro, $attributes)) {
                    $choices[$experiencePro->getUser() . " -> " . $experiencePro->getPoste()] = $experiencePro->getId();
                }
            }
        } else {
            $experiencesPro = $this->em->getRepository(ExperiencePro::class)->findAll();

            foreach ($experiencesPro as $experiencePro) {
                if ($this->hasMissingTranslations($experiencePro, $attributes)) {
                    $choices[$experiencePro->getUser() . " -> " . $experiencePro->getPoste()] = $experiencePro->getId();
                }
            }
        }
        return $choices;
    }

    private function hasMissingTranslations(ExperiencePro $experiencePro, array $attributes): bool
    {
        foreach ($attributes as $attribute) {
            $existing = $this->em->getRepository(Translation::class)->findOneBy([
                'entity' => ExperiencePro::class,
                'entityId' => $experiencePro->getId(),
                'attribute' => $attribute,
            ]);

            if (!$existing) {
                return true;
            }
        }

        return false;
    }

    private function getExperienceProAttributs(): array
    {
        return [
            'Poste' => 'poste',
            'Description' => 'description',
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Translation) {
            return;
        }

        if (
            $entityInstance->getEntity() === ExperiencePro::class &&
            $entityInstance->getEntityId()
        ) {
            $experiencePro = $this->em->getRepository(ExperiencePro::class)->find($entityInstance->getEntityId());

            if ($experiencePro) {
                $attribute = $entityInstance->getAttribute();
                $getter = 'get' . ucfirst($attribute);


                if (method_exists($experiencePro, $getter)) {
                    $value = $experiencePro->$getter();

                    $existingTranslation = $this->em->getRepository(Translation::class)->findOneBy([
                        'entity' => ExperiencePro::class,
                        'entityId' => $experiencePro->getId(),
                        'attribute' => $attribute,
                    ]);

                    if ($existingTranslation) {
                        $existingTranslation->setEn($entityInstance->getEn());
                        $existingTranslation->setEs($entityInstance->getEs());

                        if (empty($existingTranslation->getFr())) {
                            $existingTranslation->setFr($value);
                        }

                        $existingTranslation->setPersonne($experiencePro->getUser());

                        $entityManager->persist($existingTranslation);
                        $entityManager->flush();

                        return;
                    } else {
                        $entityInstance->setFr($value);
                    }
                }

                $entityInstance->setPersonne($experiencePro->getUser());
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
