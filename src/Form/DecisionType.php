<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\ClassForType\CollectionArrayTransform;
use Doctrine\Common\Collections\Collection;

class DecisionType extends AbstractType
{
    public function __construct(private UserRepository $userRepository, private GroupRepository $groupRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
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
            ->add('users', ChoiceType::class, [
                'label' => "Les personnes impactées",
                'choice_label' => 'email',
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->userRepository->findAll()
            ])
            ->add('userExpert', ChoiceType::class, [
                'choice_label' => 'email',
                'expanded' => true,
                'multiple' => true,
                'choices' => $this->userRepository->findAll()
            ])
            ->add('groupes', ChoiceType::class, [
                'label' => 'Choix des groupes',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->groupRepository->findAll(),
            ])
        ;
            $builder->get('users')->addModelTransformer(new CollectionArrayTransform());
            $builder->get('userExpert')->addModelTransformer(new CollectionArrayTransform());
            $builder->get('groupes')->addModelTransformer(new CollectionArrayTransform());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
