<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('object')
        ->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'form-control', // Add any specific form field options or classes
                'rows' => 5, // Specify the number of rows in the textarea
            ],
            'label' => 'Description', // Optional label
            'required' => true, // Adjust as needed
        ])
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Appointement Related' => 'Appointement Related',
                'Donation Related' => 'Donation Related',
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
