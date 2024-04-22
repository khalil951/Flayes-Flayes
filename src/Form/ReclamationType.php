<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('object', TextType::class, [
            'required' => false, // Set required to false if applicable
            'constraints' => [
                new Regex([
                    'pattern' => '/^[A-Za-z\s]+$/',
                    'message' => 'The object must contain only letters.',
                ]),
                new Length([
                    'max' => 20,
                    'maxMessage' => 'The object must not be longer than {{ limit }} characters.',
                ]),
                // Additional constraints...
            ],
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Enter your object',
            ],
        ])
        ->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'form-control', // Add any specific form field options or classes
                'rows' => 5, // Specify the number of rows in the textarea
            ],
            'label' => 'Description', // Optional label
            'required' => false, // Adjust as needed
        ])
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Investment Related' => 'Investment Related',
                'Event Related' => 'Event Related',
                'Website Bug' => 'Website Bug',
                'Account Problem' => 'Account Problem',
               
                // Add more options as needed
            ],
        ]);
      
       
    
       
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
