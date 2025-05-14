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

class ExperienceProType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'poste',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Poste',
                    'mapped' => false,
                ]
            )
            ->add(
                'entreprise',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Entreprise',
                    'mapped' => false,
                ]
            )
            ->add(
                'dateDebut',
                IntegerType::class,
                [
                    'required' => true,
                    'label' => 'Date debut',
                    'mapped' => false,
                ]
            )
            ->add(
                'dateFin',
                IntegerType::class,
                [
                    'required' => false,
                    'label' => 'Date fin',
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
                'submitExperiencePro',
                SubmitType::class,
                [
                    'label' => 'Ajouter'
                ]
            );
    }
}
