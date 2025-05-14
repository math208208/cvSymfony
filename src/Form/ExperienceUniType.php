<?php

namespace App\Form;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExperienceUniType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add(
                'titre',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Titre',
                    'mapped' => false,
                ]
            )
            ->add(
                'sousTitre',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Sous titre',
                    'mapped' => false,
                ]
            )
            ->add(
                'annee',
                IntegerType::class,
                [
                    'required' => true,
                    'label' => 'Annee',
                    'mapped' => false,
                ]
            )
            ->add(
                'description',
                CKEditorType::class,
                [
                    'required' => true,
                    'label' => 'Description',
                    'mapped' => false,
                ]
            )

            ->add(
                'submitExperienceUni',
                SubmitType::class,
                [
                    'label' => 'Ajouter'
                ]
            );
    }
}
