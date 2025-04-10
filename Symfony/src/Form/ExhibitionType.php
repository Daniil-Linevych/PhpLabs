<?php

namespace App\Form;

use App\Entity\Exhibition;
use App\Entity\Staff;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ExhibitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required'=>true
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('staffMembers', EntityType::class, [
                'class'=> Staff::class,
                'choice_label' => 'fullName',
                'choices' => $options['staff_members'],
                'placeholder' => 'Select a staff member',
                'multiple'=>true,
                'by_reference' => false, 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exhibition::class,
            'staff_members'=>[]
        ]);
    }
}
