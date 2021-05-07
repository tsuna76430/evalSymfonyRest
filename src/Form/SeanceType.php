<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Seance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\File;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateSeance', DateType::class, [
                'widget' => 'choice',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('duree', NumberType::class, array(
                'scale' => 1,
                'required' => true,
            ))
            ->add('titre', TextType::class, [ 
                'label' => "Titre", 
                'attr' => [ 
                    'placeholder' => "Entrez le titre de la séance" 
                ], 
                'required' => true 
                ])
            ->add('contenu', TextType::class, [ 
                'label' => "Contenu de la séance", 
                'attr' => [ 
                    'placeholder' => "Entrez le contenu de la séance" 
                ], 
                'required' => true 
                ])
            // ->add('fichier')
            ->add('fichier', FileType::class, [
                'label' => 'document de cour',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('module', EntityType::class, array(
                'class'=>Module::class,
                'choice_label'=>'nom',
                'expanded'=>false,
                'multiple'=>false,
                'required' => true 
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
