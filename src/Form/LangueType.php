<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LangueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'nomLangue',
                ChoiceType::class,
                [
                'choices' => [
                    'langue.english' => 'anglais',
                    'langue.spanish' => 'espagnol',
                    'langue.chinese' => 'chinois',
                    'langue.portuguese' => 'portugais',
                    'langue.german' => 'allemand',
                    'langue.japanese' => 'japonais',
                ],
                'required' => true,
                'label' => 'form.selectLanguage' ,
                ]
            )
            ->add(
                'niveau',
                TextType::class,
                [
                'label' => 'form.languageLevel',
                'mapped' => false,
                ]
            )

            ->add(
                'submitLangue',
                SubmitType::class,
                [
                'label' => 'form.add'
                ]
            );
    }
}
