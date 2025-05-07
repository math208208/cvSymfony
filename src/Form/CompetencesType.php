<?php

namespace App\Form;

use App\Entity\Competence;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

class CompetencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('existeCompetence', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'Sélectionner une competence existante',
                'mapped' => false,

            ])
            ->add('newCompetence', TextType::class, [
                'required' => false,
                'label' => 'Ou entrez la nouvelle competence',
                'mapped' => false,

            ])

            ->add('niveauComp', IntegerType::class, [
                'required' => true,
                'label' => 'Entrez le niveau de maîtrise',
                'mapped' => false,
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'max' => 100,
                        'notInRangeMessage' => 'Le niveau doit être entre {{ min }} et {{ max }}.',
                    ]),
                ],
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
           
            ->add('submitCompetence', SubmitType::class, [
                'label' => 'Ajouter'
            ]);
    }
}
