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
                'website' => 'http://www.johndoe.com',
                'twitter' => 'john_doe_twitter',
                'instagram' => 'john_doe_instagram',
                'facebook' => 'john_doe_facebook',
            ],
            [
                'email' => 'jane.doe@example.com',
                'roles' => ['ROLE_USER'],
                'password' => 'hashed_password_2',
                'lastname' => 'Doe',
                'firstname' => 'Jane',
                'phoneNumber' => 987654321,
                'city' => 'San Francisco',
                'occupation' => 'Designer',
                'description' => 'Creative and artistic.',
                'photo' => 'jane_doe.jpg',
                'website' => 'http://www.janedoe.com',
                'twitter' => 'jane_doe_twitter',
                'instagram' => 'jane_doe_instagram',
                'facebook' => 'jane_doe_facebook',
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
            $user->setWebsite($userDataItem['website']);
            $user->setTwitter($userDataItem['twitter']);
            $user->setInstagram($userDataItem['instagram']);
            $user->setFacebook($userDataItem['facebook']);
            $this->addReference('user_' .  $userDataItem['email'], $user);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userDataItem['password']);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $manager->flush();
        }
    }
}
