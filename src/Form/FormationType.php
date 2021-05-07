<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [ 
                'label' => "Nom de la formation", 
                'attr' => [ 
                    'placeholder' => "Entrez le nom de  la formation" 
                ], 
                'required' => true 
                ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'choice',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'choice',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('utilisateurs', EntityType::class, array(
                'class'=>Utilisateur::class,
                'choice_label'=>'nomprenom',
                'mapped' => true,
                'expanded'=>false,
                'multiple'=>true,
                'required' => false 
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
