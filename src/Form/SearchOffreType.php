<?php

namespace App\Form;


use App\Data\SearchOffre;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Component\Form\AbstractType;
use App\Repository\SousCategorieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class SearchOffreType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
           
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => SousCategorie::class,
                 'query_builder'=> function (SousCategorieRepository $f)
                {
                    return $f ->createQueryBuilder('f')
                            ->orderBy('f.title', 'ASC');
                },    
                'expanded' => true,
                'multiple' => true
            ])
            ->add('ville',TextType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'ville ...'
                ]
                
            ])

           
        ;
      
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchOffre::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}