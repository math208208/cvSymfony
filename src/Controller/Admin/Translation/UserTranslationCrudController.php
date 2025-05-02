<?php

namespace App\Controller\Admin\Translation;


use App\Entity\User;
use App\Entity\Translation\Translation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserTranslationCrudController extends AbstractCrudController
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

    //affiche seulement les objet ayant user en entité
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

                $qb->andWhere('entity.entity = :User')
                    ->setParameter('User', \App\Entity\User::class)
                    ->andWhere('entity.personne = :fullName')
                    ->setParameter('fullName', $fullName);
            }
        } else {
            $qb->andWhere('entity.entity = :User')
                ->setParameter('User', \App\Entity\User::class);
        }


        return $qb;
    }

    public function configureActions(Actions $actions): Actions
    {
        $test = Action::new('test')
            ->setLabel('Detail')
            ->linkToUrl(function (Translation $translation) {
                return 'http://localhost:8001/admin/user-translation/' . $translation->getId();
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
                    'User' => User::class,
                ])
                ->setFormTypeOption('data', User::class)
                ->setRequired(true),

            TextField::new('personne')
                ->setLabel('Personne')
                ->hideOnForm(),


            ChoiceField::new('entityId')
                ->setLabel('ID du user')
                ->setChoices($this->getUserIds())
                ->onlyOnForms(),

            ChoiceField::new('attribute')
                ->setLabel('Attribut')
                ->setChoices($this->getUserAttributs()),

            TextareaField::new('fr')
                ->setLabel('Francais')
                ->hideOnForm(),

            TextareaField::new('en')->setLabel('Anglais'),
            TextareaField::new('es')->setLabel('Espagnol'),
        ];
    }

    private function getUserIds(): array
    {

        if (!$this->isGranted('ROLE_ADMIN')) {
            $admin = $this->getUser();

            if ($admin instanceof \App\Entity\Admin) {
                $adminEmail = $admin->getEmail();
            } else {
                throw new \Exception('Utilisateur non valide.');
            }


            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $adminEmail]);

            $choices = [];

            $existingTranslation = $this->em->getRepository(Translation::class)->findOneBy([
                'entity' => User::class,
                'entityId' => $user->getId(),
            ]);

            if (!$existingTranslation) {
                $choices[$user->getPrenom() . " " . $user->getNom()] = $user->getId();
            }

            return $choices;
        } else {
            $users = $this->em->getRepository(User::class)->findAll();

            $choices = [];
            foreach ($users as $user) {
                $existingTranslation = $this->em->getRepository(Translation::class)->findOneBy([
                    'entity' => User::class,
                    'entityId' => $user->getId(),
                ]);

                if (!$existingTranslation) {
                    $choices[$user->getPrenom() . " " . $user->getNom()] = $user->getId();
                }
            }
        }

        return $choices;
    }

    private function getUserAttributs(): array
    {
        return [
            'Profession' => 'profession',
            'Description' => 'description',
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Translation) {
            return;
        }

        if (
            $entityInstance->getEntity() === User::class &&
            $entityInstance->getEntityId()
        ) {
            $user = $this->em->getRepository(User::class)->find($entityInstance->getEntityId());

            if ($user) {
                $attribute = $entityInstance->getAttribute();

                $getter = 'get' . ucfirst($attribute);
                if (method_exists($user, $getter)) {
                    $value = $user->$getter();
                    $entityInstance->setFr($value);
                }
            }
        }
        $entityInstance->setPersonne($user->__toString());

        parent::persistEntity($entityManager, $entityInstance);
    }
}
