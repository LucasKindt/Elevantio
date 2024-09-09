<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Naam',
                'attr' => [
                    'placeholder' => 'Naam',
                ],
                'row_attr' => [
                    'class' => 'form-floating my-2',
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                ],
                'row_attr' => [
                    'class' => 'form-floating my-2',
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Wachtwoord',
                'attr' => [
                    'placeholder' => 'Email',
                    'autocomplete' => 'new-password',
                ],
                'row_attr' => [
                    'class' => 'form-floating my-2',
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Voer aub een wachtwoord in',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Wachtwoord moet minimaal {{ limit }} karakters bevatten.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Ik ben akkoord met de algemene voorwaarden',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'U moet akkoord gaan om te registreren.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
