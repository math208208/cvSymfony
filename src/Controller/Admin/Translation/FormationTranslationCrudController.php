<?php

namespace App\Controller\Admin\Translation;

use App\Entity\Formation;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FormationTranslationCrudController extends AbstractCrudController
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

    //affiche seulement les objet ayant Formation en entité
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

                $qb->andWhere('entity.entity = :Formation')
                    ->setParameter('Formation', \App\Entity\Formation::class)
                    ->andWhere('entity.personne = :fullName')
                    ->setParameter('fullName', $fullName);
            }
        } else {
            $qb->andWhere('entity.entity = :Formation')
                ->setParameter('Formation', \App\Entity\Formation::class);
        }


        return $qb;
    }

    public function configureActions(Actions $actions): Actions
    {
        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(
                function (Translation $translation) {
                    return 'http://localhost:8001/admin/formation-translation/' . $translation->getId();
                }
            );


        $actions = $actions
            ->add('index', $test);

        return $actions;
    }


    public function configureFields(string $pageName): iterable
    {
        $selectedFormationId = null;

        if ($pageName === Crud::PAGE_EDIT) {
            $selectedFormationId = $this->getContext()?->getEntity()?->getInstance()?->getEntityId();
        }

        return [
            ChoiceField::new('entity')
                ->setLabel('Entité')
                ->setChoices(
                    [
                    'Formation' => Formation::class,
                    ]
                )
                ->setFormTypeOption('data', Formation::class)
                ->setRequired(true),


            TextField::new('personne')
                ->setLabel('Personne')
                ->hideOnForm(),

            ChoiceField::new('entityId')
                ->setLabel('ID de la Formation')
                ->setChoices($this->getFormationIds($selectedFormationId))
                ->onlyOnForms(),

            ChoiceField::new('attribute')
                ->setLabel('Attribut')
                ->setChoices($this->getFormationAttributs()),

            TextareaField::new('fr')
                ->setLabel('Francais')
                ->hideOnForm(),


            TextareaField::new('en')->setLabel('Anglais'),
            TextareaField::new('es')->setLabel('Espagnol'),
        ];
    }

    private function getFormationIds(?int $selectedFormationId = null): array
    {
        $attributes = array_values($this->getFormationAttributs());
        $choices = [];
        if ($selectedFormationId !== null) {
            $formation = $this->em->getRepository(Formation::class)->findOneBy(['id' => $selectedFormationId]);
            $choices[$formation->getUser() . " -> " . $formation->getIntitule()] = $formation->getId();
        } else {
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

                $formations = $this->em->getRepository(Formation::class)->findBy(['user' => $user]);
            } else {
                $formations = $this->em->getRepository(Formation::class)->findAll();
            }

            foreach ($formations as $formation) {
                $hasMissingTranslations = $this->hasMissingTranslations($formation, $attributes);

                if ($hasMissingTranslations) {
                    $choices[$formation->getUser() . " -> " . $formation->getIntitule()] = $formation->getId();
                }
            }
        }

        return $choices;
    }


    private function hasMissingTranslations(Formation $formation, array $attributes): bool
    {
        foreach ($attributes as $attribute) {
            $existing = $this->em->getRepository(Translation::class)->findOneBy(
                [
                'entity' => Formation::class,
                'entityId' => $formation->getId(),
                'attribute' => $attribute,
                ]
            );

            if (!$existing) {
                return true;
            }
        }

        return false;
    }

    private function getFormationAttributs(): array
    {
        return [
            'Intitulé' => 'intitule',
            'Lieu' => 'lieu',
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Translation) {
            return;
        }

        if (
            $entityInstance->getEntity() === Formation::class
            && $entityInstance->getEntityId()
        ) {
            $formation = $this->em->getRepository(Formation::class)->find($entityInstance->getEntityId());

            if ($formation) {
                $attribute = $entityInstance->getAttribute();
                $getter = 'get' . ucfirst($attribute);

                if (method_exists($formation, $getter)) {
                    $value = $formation->$getter();

                    $existingTranslation = $this->em->getRepository(Translation::class)->findOneBy(
                        [
                        'entity' => Formation::class,
                        'entityId' => $formation->getId(),
                        'attribute' => $attribute,
                        ]
                    );

                    if ($existingTranslation) {
                        $existingTranslation->setEn($entityInstance->getEn());
                        $existingTranslation->setEs($entityInstance->getEs());

                        if (empty($existingTranslation->getFr())) {
                            $existingTranslation->setFr($value);
                        }

                        $existingTranslation->setPersonne($formation->getUser());

                        $entityManager->persist($existingTranslation);
                        $entityManager->flush();

                        return;
                    } else {
                        $entityInstance->setFr($value);
                    }
                }

                $entityInstance->setPersonne($formation->getUser());
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
