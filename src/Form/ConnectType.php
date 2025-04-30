<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;

class ConnectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('_plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Se connecter',
            ]);
    }
}
