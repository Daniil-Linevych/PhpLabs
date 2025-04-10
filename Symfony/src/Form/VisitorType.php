<?php

namespace App\Form;

use App\Entity\Visitor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class VisitorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'required'=>true,
            ])
            ->add('email', EmailType::class,  [
                'required'=>true,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone number',
                'required' => false,
                'attr' => [
                    'placeholder' => '+123456789',
                ],
            ])
            ->add('registrationDate', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visitor::class,
        ]);
    }
}
