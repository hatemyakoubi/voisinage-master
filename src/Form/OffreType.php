<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'label'=>'Titre',
                
            ])
            ->add('description', TextareaType::class,[
                'label'=>'Déscription',
                
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
               'label' => 'Sous Catégories',
            ])
            ->add('images', FileType::class,[
                'label' => 'Images (Champs optionnel)',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
             
            ])
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
            'data_class' => Offre::class,
        ]);
    }
}
