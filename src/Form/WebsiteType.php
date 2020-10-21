<?php

namespace App\Form;

use App\Entity\Website;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class WebsiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom'
            ])
            ->add('url',UrlType::class,[
                'label'=>'URL'
            ])
                
            //->add('ipServeur',TextType::class,[
            //    'label'=>'Adresse du serveur'
            //])
            ->add('nameFolder', TextType::class,[
                'label'=>'Nom du dossier'
            ])
            ->add('pathFolder',TextType::class,[
                'label'=>'Chemin du dossier'
            ])
            ->add('valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Website::class,
        ]);
    }
}
