<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Child;
use App\Entity\ActivityDate;
use Doctrine\ORM\EntityManagerInterface;

class SignupType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $activityDates = $this->entityManager->getRepository(ActivityDate::class)->findAll();

        $builder
            ->add('children', CollectionType::class, [
                'entry_type' => ChildActivityDatesType::class,
                'entry_options' => [
                    'child' => function(Child $child) {
                        return $child;
                    },
                    'activity_dates' => $activityDates,
                ],
                'allow_add' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}