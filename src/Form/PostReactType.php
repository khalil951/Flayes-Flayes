<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\PostReact; // Import the Post entity class
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostReactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('post', EntityType::class, [ // Change 'post_id' to 'post' and use EntityType
                'class' => Post::class, // Specify the class of the related entity
                'choice_label' => 'id', // Replace 'id' with the property you want to display in the dropdown
            ])
            ->add('user_id')
            ->add('Isliked', CheckboxType::class) // Assuming 'Isliked' is a CheckboxType field
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostReact::class,
        ]);
    }
}

