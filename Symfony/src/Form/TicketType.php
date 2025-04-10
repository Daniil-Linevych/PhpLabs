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


class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', NumberType::class)
            ->add('purchaseDate', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime()
            ])
            ->add('exhibition', EntityType::class, [ 
                'class' => Exhibition::class,
                'choice_label' => 'name',
                'choices' => $options['exhibitions'],
                'placeholder' => 'Select an exhibition'
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