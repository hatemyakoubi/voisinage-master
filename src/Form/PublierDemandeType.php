<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ville;
use App\Entity\Demande;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Repository\VilleRepository;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PublierDemandeType extends AbstractType
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
                'label'=>'Déscription de demande'
            ])
            ->add('categorie', EntityType::class,[
                'class'=>Categorie::class,
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionnez votre catégorie',
                'mapped' => false,
                'required' => false,
             
            ])
           ->add('souscategorie', ChoiceType::class,[
               'placeholder' => 'Sous Catégorie (Choisir une catégorie)',
               'required' => false,
               
            ])
            ->add('budget')
 
 ;

                // addlistener pour l'ajout de  categorie et sous-categorie
            $formModifier = function (FormInterface $form, Categorie $category = null) {
            $subCategory = null === $category ? [] : $category->getSousCategories();

            $form->add('souscategorie', EntityType::class, [
                'class' => SousCategorie::class,
                'choices' => $subCategory,
                'required' => false,
                'choice_label' => 'title',
                'placeholder' => 'Sélectionneé Sous Catégorie ',
                'attr' => ['class' => 'selectcatg'],
                'label' => 'Sous Catégories',
                
            ]);
        }; 
            $builder->get('categorie')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $category = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $category);
            }
        );

       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
