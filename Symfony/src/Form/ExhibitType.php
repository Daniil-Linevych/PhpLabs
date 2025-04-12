<?php

namespace App\Form;

use App\Entity\Exhibit;
use App\Entity\Exhibition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;

class ExhibitType extends AbstractType
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
            ->add('description', TextareaType::class)
            ->add('creationYear', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Range([
                        'max' => (int) date('Y'),
                        'maxMessage' => 'Year cannot be in the future',
                    ]),
                ],
            ])
            ->add('author', TextType::class, [
                'required'=> false,
                'constraints' => [
                    new Assert\Length([
                        'max'=>255,
                        'maxMessage'=>'Author cannot be longer than {{limit}} charcters'
                    ])
                ]
            ])
            ->add('exhibition', EntityType::class, [
                'class' => Exhibition::class,
                'choice_label' => 'name',
                'choices' => $options['exhibitions'],
                'placeholder' => 'Select an exhibition',
                'constraints' => [
                    new Assert\NotNull(['message' => 'Please select an exhibition']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exhibit::class,
            'exhibitions' => []
        ]);
    }
}
