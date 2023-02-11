<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Component\Form\AbstractType;
use App\Repository\SousCategorieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieSousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('souscategories', EntityType::class,[
                'class'=> SousCategorie::class,
                'choice_label'=> 'title',
                'multiple'=>true,  
                'by_reference'=> false,
                'label'=> 'Sous Catégories', 
                 'query_builder'=> function (SousCategorieRepository $f)
                {
                    return $f ->createQueryBuilder('f')
                            ->orderBy('f.title', 'ASC');
                },    
                
                'attr'=>[
                    'class'=> 'selectcatg',
                 ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Categorie::class,
        ]);
    }
}
