<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConnectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                '_email',
                EmailType::class,
                [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'form.emailRequired']),
                    new Email(['message' => 'form.emailInvalid']),
                ]
                ]
            )
            ->add(
                '_plainPassword',
                PasswordType::class,
                [
                'label' => 'form.password',
                ]
            )
           
            ->add(
                'submit',
                SubmitType::class,
                [
                'label' => 'form.login',
                ]
            );
            
    }
}
