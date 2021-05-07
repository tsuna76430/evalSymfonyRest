<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [ 
                'label' => "Nom", 
                'attr' => [ 
                    'placeholder' => "Entrez le nom du module" 
                ], 
                'required' => true 
                ])
            ->add('nbHeures', NumberType::class, array(
                'scale' => 5,
                'required' => true,
            ))
            ->add('formation', EntityType::class, array(
                'class'=>Formation::class,
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
            'data_class' => Module::class,
        ]);
    }
}
