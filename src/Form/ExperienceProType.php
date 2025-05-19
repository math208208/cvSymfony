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
                    'label' => 'form.jobTitle',
                    'mapped' => false,
                ]
            )
            ->add(
                'entreprise',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'form.company',
                    'mapped' => false,
                ]
            )
            ->add(
                'dateDebut',
                IntegerType::class,
                [
                    'required' => true,
                    'label' => 'form.startDate',
                    'mapped' => false,
                ]
            )
            ->add(
                'dateFin',
                IntegerType::class,
                [
                    'required' => false,
                    'label' => 'form.endDate',
                    'mapped' => false,
                ]
            )
            ->add(
                'description',
                CKEditorType::class,
                [
                    'required' => true,
                    'label' => 'form.description',
                    'mapped' => false,
                ]
            )

            ->add(
                'submitExperiencePro',
                SubmitType::class,
                [
                    'label' => 'form.add'
                ]
            );
    }
}
