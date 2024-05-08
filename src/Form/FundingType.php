<?php

namespace App\Form;

use App\Entity\Funding;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class FundingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Dept Investment' => 'dept',
                    'Revenue Investment' => 'revenue',
                    'Equity Investment' => 'equity',
                ],
                'placeholder' => 'Select Funding Type',
                'label' => ' ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Funding type cannot be blank',
                    ]),
                    new Choice([
                        'choices' => ['dept', 'equity', 'revenue'],
                        'message' => 'Invalid funding type',
                    ]),
            ]
            ])
            ->add('attribute1', TextType::class, [
                'label' => 'Investment amount (usd)',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Investment amount cannot be blank',
                    ]),
                ],
            ])
            ->add('attribute2', TextType::class,['constraints' => [
                    new NotBlank([
                        'message' => 'Attribute 2 cannot be blank',
                    ]),
                    new Range([
                        'min' => 0,
                        'max' => 100,
                        'minMessage' => 'Attribute 2 must be at least {{ limit }}',
                        'maxMessage' => 'Attribute 2 cannot be greater than {{ limit }}',
                    ]),
                ],
            ])
            ->add('attribute3', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Attribute 3 cannot be blank',
                    ]),
                ],
            ])
            ->add('textattribute', ChoiceType::class, [
                'choices' => [
                    'Low' => 'Low',
                    'Medium' => 'Medium',
                    'High' => 'High',
                    'AAA'=>"AAA",
                    'AA+'=>"AA+",
                    'AA'=>"AA",
                    'A+'=>"A+",
                    'A'=>"A",
                    'BBB+'=>"BBB+",
                    'BBB'=>"BBB",
                    'BB+'=>"BB+",
                    'BB'=>"BB",
                    'On sails'=>"On sails",
                    'On revenue'=>"On revenue"
                ],
                'label' => ' ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Text attribute cannot be blank',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Funding::class,
        ]);
    }
}
