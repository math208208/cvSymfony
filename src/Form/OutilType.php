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

class OutilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('existeOutil', EntityType::class, [ 
                'class' => Outil::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'Sélectionner un outil existant',
                'mapped' => false, 
            ])
            ->add('newOutil', TextType::class, [
                'required' => false,
                'label' => 'Ou entrez un nouvel outil',
                'mapped' => false,

            ])
            ->add('newOutilImage', FileType::class, [
                'label' => 'Image de l’outil (optionnel)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (jpeg, png, webp)',
                    ]),
                ],
            ])
            ->add('submitOutil', SubmitType::class, [
                'label' => 'Ajouter'
            ]);
    }

    
}
