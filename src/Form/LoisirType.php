<?php

namespace App\Form;

use App\Entity\Loisir;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class LoisirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'existeLoisir',
                EntityType::class,
                [
                'class' => Loisir::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'form.selectistingHobby',
                'mapped' => false
                ]
            )
            ->add(
                'newLoisir',
                TextType::class,
                [
                'required' => false,
                'label' => 'form.orEnterNewHobby',
                'mapped' => false,
                ]
            )
            ->add(
                'newLoisirImage',
                FileType::class,
                [
                'label' => 'form.hobbyImage',
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
                'submitLoisir',
                SubmitType::class,
                [
                'label' => 'form.add'
                ]
            );
    }
}
