<?php

namespace App\Controller\Admin\Translation;

use App\Entity\Loisir;
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

class LoisirTranslationCrudController extends AbstractCrudController
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

    //affiche seulement les objet ayant loisir en entité
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

                $qb->andWhere('entity.entity = :Loisir')
                    ->setParameter('Loisir', \App\Entity\Loisir::class)
                    ->andWhere('entity.personne = :fullName')
                    ->setParameter('fullName', $fullName);
            }
        } else {
            $qb->andWhere('entity.entity = :Loisir')
                ->setParameter('Loisir', \App\Entity\Loisir::class);
        }


        return $qb;
    }


    public function configureActions(Actions $actions): Actions
    {
        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(
                function (Translation $translation) {
                    return 'http://localhost:8001/admin/loisir-translation/' . $translation->getId();
                }
            );


        $actions = $actions
            ->add('index', $test);

        return $actions;
    }


    public function configureFields(string $pageName): iterable
    {

        //permet d'afficher dans edit l'element car sinon il saffiche pas vu qu'il est deja traduit
        $selectedLoisirId = null;

        if ($pageName === Crud::PAGE_EDIT) {
            $selectedLoisirId = $this->getContext()?->getEntity()?->getInstance()?->getEntityId();
        }

        return [
            ChoiceField::new('entity')
                ->setLabel('Entité')
                ->setChoices(
                    [
                    'Loisir' => Loisir::class,
                    ]
                )
                ->setFormTypeOption('data', Loisir::class)
                ->setRequired(true),

            TextField::new('personne')
                ->setLabel('Personne')
                ->hideOnForm(),

            ChoiceField::new('entityId')
                ->setLabel('ID du loisir')
                ->setChoices($this->getLoisirIds($selectedLoisirId))
                ->onlyOnForms(),

            ChoiceField::new('attribute')
                ->setLabel('Attribut')
                ->setChoices($this->getLoisirAttributs()),

            TextareaField::new('fr')
                ->setLabel('Francais')
                ->hideOnForm(),

            TextareaField::new('en')->setLabel('Anglais'),
            TextareaField::new('es')->setLabel('Espagnol'),
        ];
    }

    private function getLoisirIds(?int $selectedLoisirId = null): array
    {
        $choices = [];
        if ($selectedLoisirId !== null) {
            $loisir = $this->em->getRepository(Loisir::class)->findOneBy(['id' => $selectedLoisirId]);
            $choices[$loisir->getUser() . " -> " . $loisir->getNom()] = $loisir->getId();
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

                $loisirs = $this->em->getRepository(Loisir::class)->findBy(['user' => $user]);
            } else {
                $loisirs = $this->em->getRepository(Loisir::class)->findAll();
            }

            foreach ($loisirs as $loisir) {
                $existingTranslation = $this->em->getRepository(Translation::class)->findOneBy(
                    [
                    'entity' => Loisir::class,
                    'entityId' => $loisir->getId(),
                    ]
                );

                if (!$existingTranslation) {
                    $choices[$loisir->getUser() . " -> " . $loisir->getNom()] = $loisir->getId();
                }
            }
        }
        return $choices;
    }

    private function getLoisirAttributs(): array
    {
        return [
            'Nom' => 'nom',
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Translation) {
            return;
        }

        if (
            $entityInstance->getEntity() === Loisir::class
            && $entityInstance->getEntityId()
        ) {
            $loisir = $this->em->getRepository(Loisir::class)->find($entityInstance->getEntityId());

            if ($loisir) {
                $attribute = $entityInstance->getAttribute();

                $getter = 'get' . ucfirst($attribute);
                if (method_exists($loisir, $getter)) {
                    $value = $loisir->$getter();
                    $entityInstance->setFr($value);
                }
            }
            $entityInstance->setPersonne($loisir->getUser());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
