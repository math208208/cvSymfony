<?php

namespace App\Form;

use App\Entity\Outil;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class LangueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomLangue', ChoiceType::class, [
                'choices' => [
                    'Anglais' => 'anglais',
                    'Espagnol' => 'espagnol',
                    'Chinois' => 'chinois',
                    'Portugais' => 'portugais',
                    'Allemand' => 'allemand',
                    'Japonais' => 'japonais',
                ],
                'required' => true,
                'label' => 'Sélectionnez une langue :' ,
            ])
            ->add('niveau', TextType::class, [
                'label' => 'Niveau : ',
                'mapped' => false,

            ])

            ->add('submitLangue', SubmitType::class, [
                'label' => 'Ajouter'
            ]);
    }
}
