<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\BilletType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('demiJournee', ChoiceType::class, array(
                'choices' => array(
                    'Oui' => true,
                    'Non' => false,
                ),
                'expanded' => true))
            ->add('date', TextareaType::class)
            ->add('email', EmailType::class)
            ->add('billets', CollectionType::class, array(
                    'entry_type'    => BilletType::class,
                    'allow_add'     => true,
                    'required' => true,
                    'by_reference'  => false,
                    'prototype' => true,
                )

            )
            ->add('save', SubmitType::class, array('label' => 'Create Task'))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
