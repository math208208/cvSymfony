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
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add(
                'email',
                EmailType::class,
                [
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est obligatoire.']),
                    new Email(['message' => 'Veuillez entrer un email valide.']),
                ]
                ]
            )
            ->add('telephone', TelType::class)
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                'label' => 'Mot de passe'
                ]
            )
            ->add(
                'private',
                CheckboxType::class,
                [
                'label' => 'Je souhaite que mon profil soit privé',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'checkboxClass']
                ]
            )
            ->add(
                'acceptMail',
                ChoiceType::class,
                [
                'label' => 'Je souhaite recevoir des notifications par mail ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'attr' => ['class' => 'radioClass']
                ]
            )

            ->add(
                'submit',
                SubmitType::class,
                [
                'label' => 'Commencer mon CV !'
                ]
            );
    }
}
