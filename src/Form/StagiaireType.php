<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom',TextType::class,['attr'=>['class'=>'form-control']])
            ->add('nom',TextType::class,['attr'=>['class'=>'form-control']])
            ->add('dateNaissance',DateType::class,['widget' =>'single_text'])
            ->add('sex',TextType::class,['attr'=>['class'=>'form-control']])
            ->add('adresse',TextType::class,['attr'=>['class'=>'form-control']])
            ->add('ville',TextType::class,['attr'=>['class'=>'form-control']])
            ->add('cp',TextType::class,['attr'=>['class'=>'form-control']])
            ->add('email',TextType::class,['attr'=>['class'=>'form-control']])
            ->add('photo')
            // ->add('sessions', EntityType::class,[
            //     'class'=> Session::class,
            //     'choice_label'=>'intitule',
            //     'multiple' => true,
            //     'attr'=>['class'=>'form-control']
            //     ])
            ->add('submit', SubmitType::class,['attr' => ['class' => 'btn btn-secondary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
