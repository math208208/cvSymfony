<?php

namespace App\Form;

use App\Entity\Outil;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class OutilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'existeOutil',
                EntityType::class,
                [
                'class' => Outil::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'form.selectExistingTool',
                'mapped' => false
                ]
            )
            ->add(
                'newOutil',
                TextType::class,
                [
                'required' => false,
                'label' => 'form.orEnterNewTool',
                'mapped' => false
                ]
            )
            ->add(
                'newOutilImage',
                FileType::class,
                [
                'label' => 'form.toolImage',
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
                        'mimeTypesMessage' => 'form.imageFormatError'
                        ]
                    ),
                ],
                ]
            )
            ->add(
                'submitOutil',
                SubmitType::class,
                [
                'label' => 'form.add'
                ]
            );
    }
}
