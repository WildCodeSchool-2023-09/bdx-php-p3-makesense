<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Collections\Collection;

class DecisionType extends AbstractType
{
    public function __construct(private UserRepository $userRepository)
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
                'label' => "Choix de l'utilisateur",
            //    'class' => User::class,
                'choice_label' => 'email',
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->userRepository->findAll()
            ])
            ->get('users')->addModelTransformer(new CallbackTransformer(
                function (Collection $usersAsCollection): array {
                    return $usersAsCollection->toArray();
                },
                function (array $usersAsArray): Collection {
                    return new ArrayCollection($usersAsArray);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
