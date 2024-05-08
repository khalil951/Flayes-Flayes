<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType as TypeSubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('catgory', ChoiceType::class, [
            'label' => 'Category',
            'attr' => ['class' => 'form-select'],
            'choices' => [
                'Stocks' => 'Stocks',
                'ETFs' => 'ETFs',
                'Mutual Funds' => 'Mutual Funds',
                'Commodities' => 'Commodities',
                'Cryptocurrency' => 'Cryptocurrency',
            ],
        ])
            ->add('sub_category', TextType::class, [
                'label' => 'Sub Category',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control'],
            ])
            // ->add('Submit',TypeSubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
