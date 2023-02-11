<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ville;
use App\Entity\UserImage;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                
                'label' => "Nom "
            ])
            ->add('lastname', TextType::class, [
                
                'label' => "Prénom "
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                  "placeholder" => "Email de confirmation vous sera envoyer"
                ],
                'label' => "Email"
            ])
           ->add('enabled', ChoiceType::class, [
                    
                    'choices'  => [
                     'Oui' => 'Oui',
                      'Non' => 'Non'
                    ],
                    'label'=> 'Activé'
                ]) 
           ->add('roles', ChoiceType::class, [
                    
                    'choices'  => [
                     'Utilisateur' => 'ROLE_USER',
                      'Administrateur' => 'ROLE_ADMIN'
                    ],
                ]) 
            ->add('phone', NumberType::class, [
                
                'label' => "Télèphone"
            ])
             
        ;
        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
        ));
              
      
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
