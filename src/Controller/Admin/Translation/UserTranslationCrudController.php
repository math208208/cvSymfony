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
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

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

        $selectedUserId = null;

        if ($pageName === Crud::PAGE_EDIT) {
            $selectedUserId = $this->getContext()?->getEntity()?->getInstance()?->getEntityId();
        }

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
                ->setChoices($this->getUserIds($selectedUserId))
                ->onlyOnForms(),

            ChoiceField::new('attribute')
                ->setLabel('Attribut')
                ->setChoices($this->getUserAttributs()),

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

    //permet d'avoir comme choix uniquement soit ou alors si le role est admin tout le monde
    private function getUserIds(?int $selectedUserId = null): array
    {
        $attributes = array_values($this->getUserAttributs());
        $choices = [];
        if ($selectedUserId !== null) {
            $user = $this->em->getRepository(User::class)->findOneBy(['id' => $selectedUserId]);
            $choices[$user->getPrenom() . " " . $user->getNom()] = $user->getId();
        } else {
            if (!$this->isGranted('ROLE_ADMIN')) {
                $admin = $this->getUser();

                if (!$admin instanceof \App\Entity\Admin) {
                    throw new \Exception('Utilisateur non valide.');
                }

                $user = $this->em->getRepository(User::class)->findOneBy(['email' => $admin->getEmail()]);

                if (!$user) {
                    throw new \Exception('Aucun utilisateur associé à cet administrateur.');
                }

                $hasMissingTranslations = $this->hasMissingTranslations($user, $attributes);
                $isSelected = $user->getId() === $selectedUserId;

                if ($hasMissingTranslations || $isSelected) {
                    $choices[$user->getPrenom() . " " . $user->getNom()] = $user->getId();
                }
            } else {
                $users = $this->em->getRepository(User::class)->findAll();

                foreach ($users as $user) {
                    $hasMissingTranslations = $this->hasMissingTranslations($user, $attributes);

                    if ($hasMissingTranslations) {
                        $choices[$user->getPrenom() . " " . $user->getNom()] = $user->getId();
                    }
                }
            }
        }

        return $choices;
    }


    private function hasMissingTranslations(User $user, array $attributes): bool
    {
        foreach ($attributes as $attribute) {
            $existing = $this->em->getRepository(Translation::class)->findOneBy([
                'entity' => User::class,
                'entityId' => $user->getId(),
                'attribute' => $attribute,
            ]);

            if (!$existing) {
                return true;
            }
        }

        return false;
    }


    private function getUserAttributs(): array
    {
        return [
            'Profession' => 'profession',
            'Description' => 'description',
        ];
    }

    //Permet d'ajouter le fr automatiquement lors de la créa ou update si l'element existe deja
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

                    $existingTranslation = $this->em->getRepository(Translation::class)->findOneBy([
                        'entity' => User::class,
                        'entityId' => $user->getId(),
                        'attribute' => $attribute,
                    ]);

                    if ($existingTranslation) {
                        $existingTranslation->setEn($entityInstance->getEn());
                        $existingTranslation->setEs($entityInstance->getEs());

                        if (empty($existingTranslation->getFr())) {
                            $existingTranslation->setFr($value);
                        }

                        $existingTranslation->setPersonne($user->__toString());

                        $entityManager->persist($existingTranslation);
                        $entityManager->flush();

                        return;
                    } else {
                        $entityInstance->setFr($value);
                    }
                }

                $entityInstance->setPersonne($user->__toString());
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
