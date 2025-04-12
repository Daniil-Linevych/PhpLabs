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
use Symfony\Component\Validator\Constraints as Assert;

class ExhibitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message'=>'Name is required!']),
                    new Assert\Length([
                        'max'=>255,
                        'maxMessage'=>'Name cannot be longer than {{limit}} charcters'
                    ])
                ]
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new Assert\GreaterThan([
                        'propertyPath' => 'parent.all[startDate].data',
                        'message' => 'End date must be after the start date.',
                    ]),
                ],
            ])
            ->add('staffMembers', EntityType::class, [
                'class'=> Staff::class,
                'choice_label' => 'fullName',
                'choices' => $options['staff_members'],
                'placeholder' => 'Select a staff member',
                'multiple'=>true,
                'by_reference' => false, 
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Please select at least one staff member.',
                    ]),
                ],
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
