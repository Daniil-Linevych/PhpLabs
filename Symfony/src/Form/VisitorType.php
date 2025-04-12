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
use Symfony\Component\Validator\Constraints as Assert;

class VisitorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'required'=>true,
                'constraints' => [
                    new Assert\NotBlank(['message'=>'Full Name is required!']),
                    new Assert\Length([
                        'max'=>255,
                        'maxMessage'=>'Full Name cannot be longer than {{limit}} charcters'
                    ])
                ]
            ])
            ->add('email', EmailType::class,  [
                'required'=>true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Email is required',
                    ]),
                    new Assert\Email([
                        'message' => 'Please enter a valid email address',
                    ]),
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone number',
                'required' => false,
                'attr' => [
                    'placeholder' => '+123456789',
                ],
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9\s\-]{7,20}$/',
                        'message' => 'Please enter a valid phone number',
                    ]),
                ],
            ])
            ->add('registrationDate', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'constraints' => [
                    new Assert\LessThanOrEqual([
                        'value' => 'now',
                        'message' => 'Registration date cannot be in the future',
                    ]),
                ],
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
