<?php

namespace App\Form;

use App\Entity\Session;
use App\Form\ProgrammeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            ->add('programmes',CollectionType::class,[
                'entry_type' => ProgrammeType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('stagiaires', CollectionType::class,[
                'entry_type' => StagiaireType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
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
