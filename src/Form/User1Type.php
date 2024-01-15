<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('lastname')
            ->add('firstname')
            ->add('phoneNumber')
            ->add('city')
            ->add('occupation')
            ->add('description')
            ->add('photo')
            ->add('reseau')
            ->add('isVerified')
            ->add('decision', EntityType::class, [
                'class' => Decision::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('memberGroup', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('decisions', EntityType::class, [
                'class' => Decision::class,
                'choice_label' => 'id',
                'multiple' => true,
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
