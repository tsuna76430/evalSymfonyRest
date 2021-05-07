<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('nom', TextType::class, [
                       'label' => "Votre nom",
                       'attr' => [ 'placeholder' => "Entrez votre nom" ],
                       'required' => true 
                       ]) 
                 ->add('prenom', TextType::class, [
                       'label' => "Votre prénom",
                       'attr' => [ 'placeholder' => "Entrez votre prénom" ],
                       'required' => true,
                       ]) 
                 ->add('email', EmailType::class, [
                       'label' => "Votre email",
                       'attr' => [ 'placeholder' => "Entrez votre email" ],
                       'required' => true
                        ]) 
                 ->add('password', RepeatedType::class, [
                       'type' => PasswordType::class, 'invalid_message' => "Le mot de passe et sa confirmation doivent être identiques",
                       'label' => "Votre mot de passe",
                       'required' => true,
                       'first_options' => [ 'label' => "Mot de passe", ],
                       'second_options' => [ 'label' => "Confirmez votre mot de passe", ], 
                       ])
                
                 ->add('roles', CollectionType::class, [
                       'entry_type'   => ChoiceType::class,
                       'entry_options'  => [
                          'label' => false,
                          'choices' => [
                              'étudiant' => 'ROLE_ETUDIANT',
                              'formateur' => 'ROLE_FORMATEUR',
                          ],
                         'required' => true
                     ],
                   ])
                 ->add('formations', EntityType::class, array(
                        'class'=>Formation::class,
                        'choice_label'=>'nom',
                        'expanded'=>false,
                        'multiple'=>true,
                        'required' => true 
                    ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
