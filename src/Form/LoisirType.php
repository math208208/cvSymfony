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
                'label' => 'Sélectionner un loisir existant',
                'mapped' => false
                ]
            )
            ->add(
                'newLoisir',
                TextType::class,
                [
                'required' => false,
                'label' => 'Ou entrez un nouveau loisir',
                'mapped' => false,
                ]
            )
            ->add(
                'newLoisirImage',
                FileType::class,
                [
                'label' => 'Image du loisir (optionnel)',
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
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (jpeg, png, webp)',
                        ]
                    ),
                ],
                ]
            )
            ->add(
                'submitLoisir',
                SubmitType::class,
                [
                'label' => 'Ajouter'
                ]
            );
    }
}
