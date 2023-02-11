<?php

namespace App\Form;

use App\Entity\Ville;
use App\Data\SearchData;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Component\Form\AbstractType;
use App\Repository\SousCategorieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchFormType extends AbstractType
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
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}