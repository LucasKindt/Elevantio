<?php
namespace App\Form;

use App\Entity\Child;
use App\Entity\Signup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('children', EntityType::class, [
                'class' => Child::class,
                'choices' => $options['children'],
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true, // Checkbox
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
        $resolver->setRequired('children');
    }
}