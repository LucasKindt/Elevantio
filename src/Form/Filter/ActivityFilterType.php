<?php
namespace App\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spiriit\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Spiriit\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

class ActivityFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', Filters\TextFilterType::class, [
            'label' => 'Naam',
            'row_attr' => [
                'class' => 'my-2',
            ]
        ]);
        $builder->add('date', Filters\DateRangeFilterType::class, [
            'label' => ' ',
            'left_date_options' => array('label' => 'Datum vanaf'),
            'right_date_options' => array('label' => 'Datum tot'),
            'row_attr' => [
                'class' => 'my-2',
            ],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'item_filter';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling'    => true,
            'csrf_protection'   => false,
            'validation_groups' => ['filtering'], // avoid NotBlank() constraint-related message
            'method'            => 'get',
        ]);
    }
}