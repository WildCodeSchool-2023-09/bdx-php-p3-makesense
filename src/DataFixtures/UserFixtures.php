<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // Fixture data
        $userData = [
            [
                'email' => 'john.doe@example.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'tototata',
                'lastname' => 'Doe',
                'firstname' => 'John',
                'phoneNumber' => 123456789,
                'city' => 'New York',
                'occupation' => 'Developer',
                'description' => 'Passionate about coding.',
                'photo' => 'john_doe.jpg',
                'reseau' => 'http://www.johndoe.com',
            ],
            [
                'email' => 'jane.doe@example.com',
                'roles' => ['ROLE_USER'],
                'password' => 'taratata',
                'lastname' => 'Doe',
                'firstname' => 'Jane',
                'phoneNumber' => 987654321,
                'city' => 'San Francisco',
                'occupation' => 'Designer',
                'description' => 'Creative and artistic.',
                'photo' => 'jane_doe.jpg',
                'reseau' => 'http://www.janedoe.com',
            ],

            [
                'email' => 'johan.doe@example.com',
                'roles' => ['ROLE_VISITOR'],
                'password' => 'azertyui',
                'lastname' => 'Doe',
                'firstname' => 'Johan',
                'phoneNumber' => 897653412,
                'city' => 'Las Vegas',
                'occupation' => 'Developer',
                'description' => 'Coding.',
                'photo' => 'johan_doe.jpg',
                'website' => 'http://www.johandoe.com',
                'twitter' => 'johan_doe_twitter',
                'instagram' => 'johan_doe_instagram',
                'facebook' => 'johan_doe_facebook',
            ],
        ];
        // Create User entities and persist them
        foreach ($userData as $userDataItem) {
            $user = new User();
            $user->setEmail($userDataItem['email']);
            $user->setRoles($userDataItem['roles']);
            $user->setPassword($userDataItem['password']);
            $user->setLastname($userDataItem['lastname']);
            $user->setFirstname($userDataItem['firstname']);
            $user->setPhoneNumber($userDataItem['phoneNumber']);
            $user->setCity($userDataItem['city']);
            $user->setOccupation($userDataItem['occupation']);
            $user->setDescription($userDataItem['description']);
            $user->setPhoto($userDataItem['photo']);
            $user->setReseau($userDataItem['reseau']);
            $this->addReference('user_' .  $userDataItem['email'], $user);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userDataItem['password']);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $manager->flush();
        }
    }
}
