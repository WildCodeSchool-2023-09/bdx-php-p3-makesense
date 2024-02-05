<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Picture;
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
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichFileType;

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
            ->add('photoFile', VichFileType::class, [
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_uri' => true, // not mandatory, default is true
            ])
            ->add('reseau', null, ['label' => false, 'required' => false, 'constraints' => [
                new Regex([
                    'pattern' =>
                        "^((http|https)://)[-a-zA-Z0-9@:%._\\+~#?&//=]{2,256}\\.[a-z]{2,6}\\b([-a-zA-Z0-9@:%._
            \\+~#?&//=]*)$^",
                    'message' => "Veuillez saisir une adresse URL valide"])]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
