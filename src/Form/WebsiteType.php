<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Website;
use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class WebsiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('type', EntityType::class,[
                'class'=>Type::class,
                'choice_label'=>'name'
            ] )

            ->add('customer',EntityType::class,[
                'class'=>Customer::class,
                'choice_label'=>'name'
            ])

            ->add('serverName', TextType::class,[
                'label'=>'Server name'
            ])
            ->add('nameFolder', TextType::class,[
                'label'=> 'Folder name'
            ])
            ->add('status', ChoiceType::class,[
                'label'=>'Status',
                'choices'=>['Active'=>'Active',
                            'Inactive'=>'Inactive'],
                'expanded'=> true,
                'label_attr'=>['class'=>'radio-inline']
            ])

            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Website::class,
        ]);
    }
}
