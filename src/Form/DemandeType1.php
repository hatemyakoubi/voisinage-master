<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ville;
use App\Entity\Demande;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Repository\VilleRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Choice;

class DemandeType1 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Exemple: Cherche Démenagement'
                ]
            ])
            ->add('description', TextareaType::class,[
                'label'=>'Déscription'
            ])
           ->add('publier',ChoiceType::class,[
               'choices'=>[
                   'False'=>'False',
                   'True'=> 'True',
               ]
           ])
           ->add('Modifier',SubmitType::class)
 ;

            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
