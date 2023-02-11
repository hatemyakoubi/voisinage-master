<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ville;
use App\Entity\UserImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserEditType extends AbstractType
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
                
                'label' => "Email"
            ])
           ->add('password', HiddenType::class,)
            ->add('confirm_password',  HiddenType::class)
            ->add('phone', NumberType::class, [
                
                'label' => "Télèphone"
            ])
             ->add('adress', TextType::class, [
               
               'label' => "Address"
               
            ])
           ->add('Modifier', SubmitType::class,[
               'attr'=>[
                   'class'=>'btn btn-success'
               ]
           ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => User::class,
        ]);
    }
}
