<?php 
// src/Form/Type/ReCAPTCHAType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

class ReCAPTCHAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recaptcha', CheckboxType::class, [
                'label' => 'I am not a robot',
                'mapped' => false,  // Don't persist the checkbox value
                'constraints' => [
                    new IsTrue([
                        'message' => 'Please confirm you are human.',
                    ]),
                ],
            ]);
    }
}

