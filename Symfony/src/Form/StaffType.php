<?php

namespace App\Form;

use App\Entity\Staff;
use App\Entity\Exhibition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class)
            ->add('position', TextType::class)
            ->add('phone', TextType::class)
            ->add('salary', NumberType::class)
            ->add('hireDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('exhibitions', EntityType::class, [
                'class' => Exhibition::class,
                'choice_label' => 'name',
                'choices' => $options['exhibitions'],
                'multiple' => true,
                'placeholder' => 'Select an exhibition/ehibitions',
                'by_reference' => false, 
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
            'exhibitions' => []
        ]);
    }
}
