<?php

namespace App\Form;

use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TypeType extends AbstractType
{
    private $translator;
    
    public function __construct(TranslatorInterface $translator){
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('IpServer', TextType::class,[
                'label'=>$this->translator->trans('Ip Server')
            ])
            ->add('path',TextType::class,[
                'label'=>$this->translator->trans('Path')
            ])
            ->add('name', TextType::class,[
                'label'=>$this->translator->trans('Name')
            ])

            ->add($this->translator->trans('submit'), SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Type::class,
        ]);
    }
}
