<?php

namespace App\Form;

use App\Entity\Offer;
use App\Entity\Funding;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferFundingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title' ,TextType::class)
            ->add('description')
            ->add('status')
//            ->add('dateCreated')
//            ->add('funding')
//            ->add('project')
//            ->add('user')
            ->add('type')
            ->add('attribute1')
            ->add('attribute2')
            ->add('attribute3')
            ->add('textattribute')
            ->add('score')
            ->add('Submit', SubmitType::class)
//            ->add('drop');
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
