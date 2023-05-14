<?php

namespace App\Form;

use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule',TextType::class,['attr'=>['class'=>'form-control']])
            ->add('dateDebut', DateType::class,[
                'widget' =>'single_text',
                'attr'=>['class'=>'form-control']
            ])
            ->add('dateFin',DateType::class,[
                'widget' =>'single_text',
                'attr'=>['class'=>'form-control']
                ])
            ->add('nbPlaces',IntegerType::class,['attr'=>['class'=>'form-control']])
            ->add('stagiaires')
            ->add('submit', SubmitType::class,['attr' => ['class' => 'btn btn-secondary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
