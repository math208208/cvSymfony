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

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'form.name'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'form.lastName'
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
            ->add('telephone', TelType::class)
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'label' => 'form.password'
                ]
            )
            ->add(
                'private',
                CheckboxType::class,
                [
                    'label' => 'form.privateProfile',
                    'required' => false,
                    'attr' => ['class' => 'checkboxClass']
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'form.startMyCv'
                ]
            );
    }
}
