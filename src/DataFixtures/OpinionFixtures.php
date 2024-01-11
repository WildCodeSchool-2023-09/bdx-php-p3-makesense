<?php

namespace App\DataFixtures;

use App\Entity\Opinion;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OpinionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $decision = $this->getReference('decision_0');

        for ($i = 0; $i < 5; $i++) {
            $opinion = new Opinion();
            $opinion->setText('text');
            $opinion->setCreatedAt(new DateTimeImmutable());
            $opinion->setDecision($decision);
            $manager->persist($opinion);
        }
            $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DecisionFixtures::class,
        ];
    }
}
