<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('status')
            ->add('description')
            ->add('impact')
            ->add('context')
            ->add('benefits')
            ->add('risk')
            ->add('startingDate')
            ->add('deadlineOpinion')
            ->add('deadlineDecision')
            ->add('deadlineConflict')
            ->add('deadlineFinal')
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'multiple' => true,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
