<?php

namespace App\Form;

use App\Entity\Child;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserChildrenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder->add('children', CollectionType::class, [
//            'entry_type' => Child::class, // Use a separate form for each child entity
//            'entry_options' => ['label' => false],
//            'allow_add' => true,
//            'allow_delete' => true,
//            'by_reference' => false, // This is important to make add/remove work
//            'prototype' => true, // If you want JS to handle dynamic adding/removing
//        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
