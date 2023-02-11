<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ville;
use App\Entity\UserImage;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                
                'label' => "Nom d'utilisateur"
            ])
            ->add('lastname', TextType::class, [
                
                'label' => "Prénom d'utilisateur"
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                  "placeholder" => "Email de confirmation vous sera envoyer"
                ],
                'label' => "Email"
            ])
            ->add('password', PasswordType::class, [
                
                'label' => "Mot de passe"
            ])
            ->add('confirm_password',  PasswordType::class)
            ->add('phone', NumberType::class, [
                
                'label' => "Télèphone"
            ])
             ->add('adress', EntityType::class, [
                'class'=>Ville::class,
                'placeholder'=>'Choisir votre ville',
               
               
            ])
           
            
        ;
              
      
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
