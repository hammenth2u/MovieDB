<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            // ->add('createdAt')
            // ->add('updatedAt')

            // On précise deux options qui servent à changer le select en une série de checkboxes
            // la valeur null permet de ne pas avori à préciser qu'on veut un ChoiceType::class
            //     alors que c'était le type de champs que Symfony allait mettre pour nous
            ->add('genres', null, [
                'expanded' => true,
                'multiple' => true,
            ])
            // Ici on n'ajoute pas le bouton submit puisque
            // les vues créées par le make:crud s'en chargent pour nous


            // S04E16 - On ajoute un chamlps image à Movie et on utilise un service pour upload l'image
            // On ajout un champs pour joindre un fichier au formulaire
            ->add('imageFile', FileType::class, [
                'data_class' => null,
                'required' => false,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
