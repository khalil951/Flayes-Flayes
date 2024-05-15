<?php

// src/Form/EventAddFormType.php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Date; // Import the Date constraint
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EventAddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $options['data'] ?? null;
        $isUpdate = $entity && $entity->getIdevent(); // Check if it's an update operation

        $builder
            ->add('name')
            ->add('date', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{2}-\d{2}-\d{4}$/',
                        'message' => 'The date format should be dd-mm-yyyy.',
                    ]),
                ],
            ])
            
            ->add('description')
            ->add('location', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Z].*$/',
                        'message' => 'The first character of the location should be uppercase.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG, PNG, or GIF file)',
                'required' => !$isUpdate, // Image is required for add operation
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Please upload a valid JPEG, PNG, or GIF image.',
                    ]),
                ], 
            ])
            
            ->add('idcat', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
