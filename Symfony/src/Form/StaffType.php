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
use Symfony\Component\Validator\Constraints as Assert;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message'=>'Full Name is required!']),
                    new Assert\Length([
                        'max'=>255,
                        'maxMessage'=>'Full Name cannot be longer than {{limit}} charcters'
                    ])
                ]
            ])
            ->add('position', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message'=>'Position is required!']),
                    new Assert\Length([
                        'max'=>255,
                        'maxMessage'=>'Position cannot be longer than {{limit}} charcters'
                    ])
                ]
            ])
            ->add('phone', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Phone number is required.']),
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9\s\-]{7,20}$/',
                        'message' => 'Please enter a valid phone number.',
                    ]),
                ],
            ])
            ->add('salary', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Salary is required.']),
                    new Assert\Type([
                        'type' => 'numeric',
                        'message' => 'Salary must be a number.',
                    ]),
                    new Assert\Positive([
                        'message' => 'Salary must be a positive number.',
                    ]),
                ],
            ])
            ->add('hireDate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('exhibitions', EntityType::class, [
                'class' => Exhibition::class,
                'choice_label' => 'name',
                'choices' => $options['exhibitions'],
                'multiple' => true,
                'placeholder' => 'Select an exhibition/ehibitions',
                'by_reference' => false, 
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Please select at least one exhibition.',
                    ]),
                ],
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
