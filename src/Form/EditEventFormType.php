<?php

namespace App\Form;
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            ->add('location', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Z].*$/',
                        'message' => 'The first character of the location should be uppercase.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Event Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif', // Add support for GIF images
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file.',
                    ]),
                ],
            ])
            ->add('idcat', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field is required.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
