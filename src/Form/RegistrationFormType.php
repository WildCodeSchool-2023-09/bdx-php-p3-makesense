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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'attr' => ['placeholder' => 'Entrez votre adresse e-mail'],
        ])

            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ])],
            ])
            ->add('plainPassword', PasswordType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                /*'placeholder' => 'coucou',*/
                'attr' => ['autocomplete' => 'new-password',
                    'placeholder' => 'Entrez votre mot de passe',
                    ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['placeholder' => 'Entrez votre prénom'],
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['placeholder' => 'Entrez votre nom de famille'],
            ])
            ->add('phoneNumber', TelType::class, [
                'attr' => ['placeholder' => 'Entrez votre numéro de téléphone'],
            ])
            ->add('city', TextType::class, [
                'attr' => ['placeholder' => 'Entrez votre ville'],
            ])
            ->add('occupation', TextType::class, [
                'attr' => ['placeholder' => 'Entrez votre profession'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['placeholder' => 'Entrez votre description'],
            ])
            ->add('photo', TextType::class)
            ->add('reseau', UrlType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Entrez l\'URL de votre réseau social'],
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
