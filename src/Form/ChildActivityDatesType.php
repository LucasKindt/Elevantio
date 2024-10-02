<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Child;

class ChildActivityDatesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $activityDates = $options['activity_dates'];

        $builder
            ->add('child', CollectionType::class, [
                'entry_type' => ChildType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                ]);

        $builder
            ->add('activityDates', ChoiceType::class, [
                'choices' => $activityDates,
                'choice_label' => function ($activityDate) {
                    return $activityDate->getDate()->format('Y-m-d');
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'Datum',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'children' => [],
            'activity_dates' => [],
        ]);
    }
}