<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Add this line
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class CatFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'constraints' => [
 
                new Length([
                    'min' => 3,
                    'minMessage' => 'Name must be at least 3 characters long.',
                    // You can also set a max limit if needed:
                    // 'max' => 50,
                    // 'maxMessage' => 'Name cannot be longer than 50 characters.',
                ])
            ],
        ])
    
            ->add('type')
            ->add('target', ChoiceType::class, [
                'choices' => [
                    'User' => 'user',
                    'Subscriber' => 'subscriber',
                ],
                'constraints' => [
                    new Choice([
                        'choices' => ['user', 'subscriber'],
                        'message' => 'Invalid target value.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'submit', // Customize the label of the submit button
                'attr' => ['class' => 'btn btn-primary'], // Add CSS class to the submit button
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
