<?php

namespace App\DataFixtures;

use App\Entity\Favori;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FavoriFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $favori = new Favori();
        $favori->setDecision($this->getReference('decision_0'));
        $favori->setUser($this->getReference('user_jane.doe@example.com'));

         $manager->persist($favori);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DecisionFixtures::class,
            UserFixtures::class,
        ];
    }
}
