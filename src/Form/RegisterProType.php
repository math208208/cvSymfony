<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterProType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'form.lastName'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'form.name'
            ])
            ->add('metier', TextType::class, [
                'label' => 'form.jobTitle'
            ])
            ->add('entreprise', TextType::class, [
                'label' => 'form.company'
            ])
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [
                        new NotBlank(['message' => 'form.emailRequired']),
                        new Email(['message' => 'form.emailInvalid']),
                    ]
                ]
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'label' => 'form.password'
                ]
            )

            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'form.start'
                ]
            );
    }
}
