<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Exhibition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;


class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', NumberType::class, [
                'constraints'=>[
                    new Assert\NotBlank(['message' => 'Salary is required.']),
                    new Assert\Type([
                        'type' => 'numeric',
                        'message' => 'Salary must be a number.',
                    ]),
                    new Assert\Positive([
                        'message' => 'Salary must be a positive number.',
                    ]),
                ]
            ])
            ->add('purchaseDate', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'required'=>true,
            ])
            ->add('exhibition', EntityType::class, [ 
                'class' => Exhibition::class,
                'choice_label' => 'name',
                'choices' => $options['exhibitions'],
                'placeholder' => 'Select an exhibition',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please select an exhibition.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'exhibitions' => [],
        ]);
    }
}