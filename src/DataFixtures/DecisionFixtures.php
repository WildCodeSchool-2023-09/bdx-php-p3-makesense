<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Decision;
use Monolog\DateTimeImmutable;

class DecisionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $decisionData = [
            [
                'title' => 'Decision Title 1',
                'status' => true,
                'description' => 'Description 2',
                'impact' => 'Impact 1',
                'context' => 'Context 1',
                'benefits' => 'Benefits 1',
                'risk' => 'Risk 1',
                'startingDate' => \Monolog\DateTimeImmutable::createFromFormat(
                    'Y-m-d',
                    '2024-02-05',
                ),
                'deadlineOpinion' => \Monolog\DateTimeImmutable::createFromFormat(
                    'Y-m-d',
                    '2024-02-10',
                ),
                'deadlineDecision' => \Monolog\DateTimeImmutable::createFromFormat(
                    'Y-m-d',
                    '2024-02-15',
                ),
                'deadlineConflict' => \Monolog\DateTimeImmutable::createFromFormat(
                    'Y-m-d',
                    '2024-02-20',
                ),
                'deadlineFinal' => \Monolog\DateTimeImmutable::createFromFormat(
                    'Y-m-d',
                    '2024-02-25',
                ),
                'user' => 'user_johan.doe@example.com',
                'group' => $this->getReference("groupe"),
                'owner' => 'john.doe@example.com',
            ],
            [
                'title' => 'Decision Title 2',
                'status' => true,
                'description' => 'Description 2',
                'impact' => 'Impact 2',
                'context' => 'Context 2',
                'benefits' => 'Benefits 2',
                'risk' => 'Risk 2',
                'startingDate' => new DateTimeImmutable(true),
                'deadlineOpinion' => new DateTimeImmutable(true),
                'deadlineDecision' => new DateTimeImmutable(true),
                'deadlineConflict' => new DateTimeImmutable(false),
                'deadlineFinal' => new DateTimeImmutable(false),
                'user' => 'user_johan.doe@example.com',
                'group' => $this->getReference("groupe"),
                'owner' => 'john.doe@example.com',
            ],
        ];

        foreach ($decisionData as $decisionDataItem) {
            $decision = new Decision();
            $decision->setTitle($decisionDataItem['title']);
            $decision->setStatus($decisionDataItem['status']);
            $decision->setDescription($decisionDataItem['description']);
            $decision->setImpact($decisionDataItem['impact']);
            $decision->setContext($decisionDataItem['context']);
            $decision->setBenefits($decisionDataItem['benefits']);
            $decision->setRisk($decisionDataItem['risk']);
            $decision->setStartingDate($decisionDataItem['startingDate']);
            $decision->setDeadlineOpinion($decisionDataItem['deadlineOpinion']);
            $decision->setDeadlineDecision($decisionDataItem['deadlineDecision']);
            $decision->setDeadlineConflict($decisionDataItem['deadlineConflict']);
            $decision->setDeadlineFinal($decisionDataItem['deadlineFinal']);
            $decision->addUser($this->getReference($decisionDataItem['user']));
            $decision->addGroupe($decisionDataItem['group']);
            $decision->setOwner($this->getReference('user_' . $decisionDataItem['owner']));

            $this->addReference('decision_' . $decisionDataItem['title'], $decision);

            $manager->persist($decision);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            GroupFixtures::class,
        ];
    }
}
