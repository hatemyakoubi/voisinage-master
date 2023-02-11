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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DemandeType extends AbstractType
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
            ->add('categorie', EntityType::class,[
                'class'=>Categorie::class,
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionnez votre catégorie',
                'mapped' => false,
                'required' => false,
                
            ])
           ->add('souscategorie', ChoiceType::class,[
               'placeholder' => 'Sous Catégorie (Choisir une catégorie)',
               'required' => false
            ])
            ->add('budget')
           ->add('user', EntityType::class,[
                'class'=>User::class,
                'label'=>'username',
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

        //edit 
      /*  $builder ->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event)
            {
                $form = $event->getForm();
                $data = $event->getData();
                $souscategorie = $data->getSouscategorie();
                if ($souscategorie) {
                    $form->get('categorie')->getData($souscategorie->getCategorie());
                    $form->add('souscategorie', EntityType::class, [
                    'class' => SousCategorie::class,
                    'choices' => $souscategorie->getCategorie()->getSouscategories(),
                    'required' => false,
                    'choice_label' => 'title',
                    'placeholder' => 'Sélectionneé Sous Catégorie ',
                    'attr' => ['class' => 'selectcatg'],
                    'label' => 'Sous Catégories',
                    'multiple'=>true
                    ]);
                }else{
                     $form->add('souscategorie', EntityType::class, [
                    'class' => SousCategorie::class,
                    'choices' => [],
                    'placeholder' => 'Sélectionneé Sous Catégorie '
                     ]);
                   
                }
            }
        );*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
