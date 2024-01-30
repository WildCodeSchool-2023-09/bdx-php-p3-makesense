<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('description', CKEditorType::class)
            ->add('impact', CKEditorType::class)
            ->add('context', CKEditorType::class)
            ->add('benefits', CKEditorType::class)
            ->add('risk', CKEditorType::class)
            ->add('startingDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('deadlineOpinion', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('deadlineDecision', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('deadlineConflict', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('deadlineFinal', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('users', ChoiceType::class, [
                'label' => "Les personnes impactées*",
                'choice_label' => 'email',
                'multiple' => true,
                'autocomplete' => true,
                'choices' => $this->userRepository->findAll()
            ])
            ->add('userExpert', ChoiceType::class, [
                'label' => 'Les personnes expertes*',
                'choice_label' => 'email',
                'autocomplete' => true,
                'multiple' => true,
                'choices' => $this->userRepository->findAll()
            ])
            ->add('groupes', ChoiceType::class, [
                'label' => 'Choix des groupes',
                'choice_label' => 'name',
                'autocomplete' => true,
                'multiple' => true,
                'required' => false,
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
