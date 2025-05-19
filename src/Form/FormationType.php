<?php

namespace App\Form;

use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'intitule',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'form.label',
                    'mapped' => false,
                ]
            )
            ->add(
                'annee',
                IntegerType::class,
                [
                'required' => true,
                'label' => 'form.year',
                'mapped' => false,
                ]
            )
            ->add(
                'lieu',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'form.location',
                    'mapped' => false,
                ]
            )
            ->add(
                'formationImage',
                FileType::class,
                [
                'label' => 'form.trainingImage',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File(
                        [
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'form.imageFormatError',
                        ]
                    ),
                ],
                ]
            )



            ->add(
                'submitFormation',
                SubmitType::class,
                [
                    'label' => 'form.add'
                ]
            );
    }
}
