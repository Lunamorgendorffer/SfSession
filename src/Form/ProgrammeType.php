<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\ModuleSession;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('duree')
            ->add('session', EntityType::class, [
                'class'=> Session::class,
                'choice_label'=> function (Session $session){
                    return $session->getintitule();
                },
                ])


            ->add('modules', EntityType::class,[     
                'class'=> ModuleSession::class,
                'choice_label'=>function (ModuleSession $module){
                    return $module->getintitule();
                },
            ])
            ->add('submit', SubmitType::class,['attr' => ['class' => 'btn btn-secondary']])
        ;
    }
        
   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
