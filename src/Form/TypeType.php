<?php

namespace App\Form;

use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TypeType extends AbstractType
{
    private $translator;
    
    public function __construct(TranslatorInterface $translator){
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>$this->translator->trans('Name')
            ])
            ->add('IpServer',TextType::class,[
                'label'=>$this->translator->trans('Ip Server')
            ])
            ->add('port',NumberType::class)

            ->add('username',TextType::class,[
                'label'=>$this->translator->trans('Username')
            ])
            ->add('password',TextType::class,[
                'label'=>$this->translator->trans('Password')
            ])
            ->add('path',TextType::class,[
                'label'=>$this->translator->trans('Path')
            ])

            ->add('serverName', TextType::class,[
                'label'=>$this->translator->trans('Server Name')
            ])

            ->add('command', TextareaType::class,[
                'label'=>$this->translator->trans('Command')
            ])

            ->add($this->translator->trans('Submit'),SubmitType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Type::class,
        ]);
    }
}
