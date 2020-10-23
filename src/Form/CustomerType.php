<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {             
        $builder
            ->add('status',ChoiceType::class,[
                'label'=>'Statut', 
                'choices'=>['Test'=> 'Test',
                            'Formation'=>'Formation',
                            'Production'=>'Production',
                            'Fermé'=>'Ferme'],
                'expanded'=> true,
                'label_attr'=>[
                    'class'=>'radio-inline'
                ]
            ])
            ->add('name', TextType::class,[
                'label'=>'Nom'
            ])
            ->add('mail',EmailType::class,[
                'label'=>'E-Mail'
            ])
            ->add('phone',TelType::class,[
                'label'=>'Telephone',
                'required'=>false
            ])
            ->add('Valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
