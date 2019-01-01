<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\CallbackTransformer;
use App\Form\BilletType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('demiJournee', CheckboxType::class, array('required' => false, 'attr' => array('class'=>'magic-checkbox')))
            ->add('date', TextType::class, array(
                'attr' => ['class' => 'input-sm form-control']))
            ->add('email', EmailType::class)
            ->add('billets', CollectionType::class, array(
                    'entry_type'    => BilletType::class,
                    'allow_add'     => true,
                    'allow_delete'     => true,
                    'required' => true,
                    'by_reference'  => false,
                    'prototype' => true,
                )

            )
            ->add('save', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
