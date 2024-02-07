<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', null, [
                'label' => 'Nom',
            ])
            ->add('firstname', null, [
                'label' => 'Prénom',
            ])
            ->add('email', null, [
                'label' => 'Adresse e-mail',
            ])
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Member' => 'ROLE_MEMBER',
                    'Admin' => 'ROLE_ADMIN',
                ],
            ])
            ->add('phoneNumber', null, [
                'label' => 'Numéro de téléphone',
            ])
            ->add('city', null, [
                'label' => 'Ville',
            ])
            ->add('occupation', null, [
                'label' => 'Profession',
            ])
            ->get('roles')->addModelTransformer(new CallbackTransformer(
                function (array $rolesAsArray): ?string {
                    return count($rolesAsArray) ? $rolesAsArray[0] : null;
                },
                function (string $rolesAsString): array {
                    return  [$rolesAsString];
                }
            ))
            /* ->add('decision', EntityType::class, [
               'class' => Decision::class,
               'choice_label' => 'id',
               'multiple' => true,
           ])
           />add('memberGroup', EntityType::class, [
                 'class' => Group::class,
                 'choice_label' => 'id',
                 'multiple' => true
             ])
             ->add('decisions', EntityType::class, [
                 'class' => Decision::class,
                 'choice_label' => 'id',
                 'multiple' => true
             ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
