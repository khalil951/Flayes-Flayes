<?php
namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Ticket;
use App\Entity\Event;
use App\Entity\User;

class TicketFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('qrcode')
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => function (Event $event) {
                    return sprintf('%s (%s)', $event->getName(), $event->getDate());
                },
                'attr' => ['class' => 'form-control']
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
