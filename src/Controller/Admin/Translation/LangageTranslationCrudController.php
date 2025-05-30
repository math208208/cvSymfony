<?php

namespace App\Controller\Admin\Translation;

use App\Entity\Langage;
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
use Symfony\Component\Validator\Constraints\Choice;

class LangageTranslationCrudController extends AbstractCrudController
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

    //affiche seulement les objet ayant langage en entité
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

                $qb->andWhere('entity.entity = :Langage')
                    ->setParameter('Langage', \App\Entity\Langage::class)
                    ->andWhere('entity.personne = :fullName')
                    ->setParameter('fullName', $fullName);
            }
        } else {
            $qb->andWhere('entity.entity = :Langage')
                ->setParameter('Langage', \App\Entity\Langage::class);
        }


        return $qb;
    }


    public function configureActions(Actions $actions): Actions
    {
        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(
                function (Translation $translation) {
                    return 'http://localhost:8001/admin/langage-translation/' . $translation->getId();
                }
            );


        $actions = $actions
            ->add('index', $test);

        return $actions;
    }


    public function configureFields(string $pageName): iterable
    {
        $selectedLangageId = null;

        if ($pageName === Crud::PAGE_EDIT) {
            $selectedLangageId = $this->getContext()?->getEntity()?->getInstance()?->getEntityId();
        }

        return [
            ChoiceField::new('entity')
                ->setLabel('Entité')
                ->setChoices(
                    [
                    'Langage' => Langage::class,
                    ]
                )
                ->setFormTypeOption('data', Langage::class)
                ->setRequired(true),


            TextField::new('personne')
                ->setLabel('Personne')
                ->hideOnForm(),

            ChoiceField::new('entityId')
                ->setLabel('ID du langage')
                ->setChoices($this->getLangageIds($selectedLangageId))
                ->onlyOnForms(),

            ChoiceField::new('attribute')
                ->setLabel('Attribut')
                ->setChoices($this->getLangageAttributs()),

            TextareaField::new('fr')
                ->setLabel('Francais')
                ->hideOnForm(),

            TextareaField::new('en')->setLabel('Anglais'),
            TextareaField::new('es')->setLabel('Espagnol'),
        ];
    }

    private function getLangageIds(?int $selectedLangageId = null): array
    {
        $choices = [];
        if ($selectedLangageId !== null) {
            $langage = $this->em->getRepository(Langage::class)->findOneBy(['id' => $selectedLangageId]);
            $choices[$langage->getUser() . " -> " . $langage->getNomLangue()] = $langage->getId();
        } else {
            if (!$this->isGranted('ROLE_ADMIN')) {
                $admin = $this->getUser();

                if (!$admin instanceof \App\Entity\Admin) {
                    return [];
                }

                $adminEmail = $admin->getEmail();
                $user = $this->em->getRepository(User::class)->findOneBy(['email' => $adminEmail]);

                if (!$user) {
                    return [];
                }

                $langages = $this->em->getRepository(Langage::class)->findBy(['user' => $user]);
            } else {
                $langages = $this->em->getRepository(Langage::class)->findAll();
            }

            foreach ($langages as $langage) {
                $existingTranslation = $this->em->getRepository(Translation::class)->findOneBy(
                    [
                    'entity' => Langage::class,
                    'entityId' => $langage->getId(),
                    ]
                );

                if (!$existingTranslation) {
                    $choices[$langage->getUser() . " -> " . $langage->getNomLangue()] = $langage->getId();
                }
            }
        }


        return $choices;
    }

    private function getLangageAttributs(): array
    {
        return [
            'Nom Langue' => 'nomLangue',
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Translation) {
            return;
        }

        if (
            $entityInstance->getEntity() === Langage::class
            && $entityInstance->getEntityId()
        ) {
            $langage = $this->em->getRepository(Langage::class)->find($entityInstance->getEntityId());

            if ($langage) {
                $attribute = $entityInstance->getAttribute();

                $getter = 'get' . ucfirst($attribute);
                if (method_exists($langage, $getter)) {
                    $value = $langage->$getter();
                    $entityInstance->setFr($value);
                }
            }
            $entityInstance->setPersonne($langage->getUser());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
