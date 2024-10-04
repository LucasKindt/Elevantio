<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\ActivityDate;
use App\Entity\Child;
use App\Entity\Signup;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotNull;

class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Dynamically filter the children belonging to the logged-in user using a query builder
        $builder
            ->add('child', EntityType::class, [
                'class' => Child::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.parent = :user') // Assuming 'parent' is the field in the Child entity referencing the User
                        ->setParameter('user', $options['user']); // Pass the logged-in user dynamically
                },
                'required' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'Please select an option or Other'
                    ]),
                ],
                'label' => 'Selecteer Kind',
            ])
            ->add('activityDate', EntityType::class, [
                'class' => ActivityDate::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('a') // Fetch all activity dates, or filter based on your needs
                    ->where('a.Activity = :activity') // Assuming 'parent' is the field in the Child entity referencing the User
                    ->setParameter('activity', $options['activity']); // Pass the logged-in user dynamically
                    ;
                },
                'choice_label' => function (ActivityDate $activityDate) {
                    return $activityDate->getDate()->format('l F d   Y - H:i'); // Format the DateTime object as a string
                },
                'multiple' => true, // Allow selection of multiple dates
                'expanded' => true, // Display as checkboxes or multiselect
                'required' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'Please select an option or Other'
                    ]),
                ],
                'label' => 'Selecteer Datums',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Link the form to the Signup entity
            'activity' => null, // Add the 'activity' option with a default of null
            'user' => null, // Add the 'user' option with a default of null
        ]);
    }
}
