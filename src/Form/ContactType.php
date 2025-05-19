<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'message',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'form.message',
                    'mapped' => false,
                ]
            )

            ->add(
                'submitContact',
                SubmitType::class,
                [
                    'label' => 'form.send'
                ]
            );
    }
}
