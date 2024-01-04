<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Decision;
use Monolog\DateTimeImmutable;

class DecisionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $decision = new Decision();
            $decision->setTitle('Title' . $i);
            $decision->setStatus(true);
            $decision->setDescription('description' . $i);
            $decision->setImpact('impact' . $i);
            $decision->setContext('context' . $i);
            $decision->setBenefits('benefits' . $i);
            $decision->setRisk('risk' . $i);
            $decision->setStartingDate(new DateTimeImmutable(true));
            $decision->setDeadlineOpinion(new DateTimeImmutable(true));
            $decision->setDeadlineDecision(new DateTimeImmutable(true));
            $decision->setDeadlineConflict(new DateTimeImmutable(false));
            $decision->setDeadlineFinal(new DateTimeImmutable(false));
            $this->addReference('decision_' . $i, $decision);

            $manager->persist($decision);
        }
        $manager->flush();
    }
}
