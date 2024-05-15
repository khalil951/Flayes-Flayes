<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Room;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Repository\RoomRepository;

class PostType extends AbstractType
{

    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        ->add('author', TextType::class, [
            'label' => 'author',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('content', TextType::class, [
            'label' => 'content',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('img_url', FileType::class, [
            'label' => 'Upload Image',
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new Assert\File([
                    'maxSize' => '100M',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image file',
                ])
            ],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            //'room' => null,
        ]);
    }
}
