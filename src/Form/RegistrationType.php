<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false, // Set required to false
                'label' => 'Your Name', 
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Enter Your Name',
            ],
            ])
            ->add('email', TextType::class, [
                'required' => false, // Set required to false
                'label' => 'Your Email', 
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Invalid email format.',
                    ]),
                    // Additional constraints...
                ],
                'attr' => ['class' => 'form-control',
                'placeholder' => 'Enter Your Email',
            ], // Set attributes directly under the 'attr' option

            ])
            
            ->add('tel', TextType::class, [
                'required' => false, // Set required to false
                'label' => 'Your Phone', 
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'The telephone number must be exactly 8 digits long.'
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'The telephone number must contain only numbers.'
                    ]),
                    // Additional constraints...
                ],
                'attr' => ['class' => 'form-control',
                'placeholder' => 'Enter Your phone',
            ], // Set attributes directly under the 'attr' option
                
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => false, // Set required to false
                'first_options' => ['label' => 'Your Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                        'message' => 'Your password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.',
                    ]),
                ],
                'options' => ['attr' => ['class' => 'form-control']],
                'attr' => [
                    'autocomplete' => 'new-password', // Hide browser autocomplete suggestions for password
                    'placeholder' => 'Enter Your Password',
                ]
            ])
            ->add('imageFile', VichFileType::class, [
                'label' => 'Your profile image (Image files only)',
                'required' => false, // make it optional
                
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
