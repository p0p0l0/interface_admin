<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CustomerType extends AbstractType
{
    private $translator;
    
    public function __construct(TranslatorInterface $translator){
        $this->translator = $translator;
    }       

    public function buildForm(FormBuilderInterface $builder, array $options)
    {             
        $builder
            ->add('status',ChoiceType::class,[
                'label'=>$this->translator->trans('Status'), 
                'choices'=>['Test'=>'Test',
                            $this->translator->trans('Training')=>$this->translator->trans('Training'),
                            'Production'=>'Production',
                            $this->translator->trans('Closed')=>$this->translator->trans('Closed')],
                'expanded'=> true,
                'label_attr'=>[
                    'class'=>'radio-inline'
                ]
            ])
            ->add('name', TextType::class,[
                'label'=>$this->translator->trans('Name')
            ])
            ->add('mail',EmailType::class,[
                'label'=>'E-Mail'
            ])
            ->add('phone',TelType::class,[
                'label'=>$this->translator->trans('Phone'),
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
