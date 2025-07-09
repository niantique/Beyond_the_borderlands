<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Stop;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

        private const DATA_DIR = __DIR__ . '/data';

    public function load(ObjectManager $manager): void
    {
        $fileContents = file_get_contents(self::DATA_DIR . '/user.json');
        $userArray = json_decode($fileContents, true);

        $userMap = [];
        $id = 1;

        $regularUser = new User();
        $regularUser
        ->setEmail('regular@user.com')
        ->setUsername('regular')
        ->setPicture("https://images.unsplash.com/photo-1611880147493-7542bdb0f024?q=80&w=1333&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D")
        ->setPassword($this->hasher->hashPassword($regularUser, 'test'));
        $manager->persist($regularUser);
        $userMap[$id++] = $regularUser;

        $adminUser = new User();
        $adminUser
        ->setEmail('admin@user.com')
        ->setUsername('admin')
        ->setPicture("https://images.unsplash.com/photo-1487164697898-db7bfc2b7bf5?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D")
        ->setRoles(['ROLE_ADMIN'])
        ->setPassword($this->hasher->hashPassword($adminUser, 'admin'));
        $manager->persist($adminUser);
        $userMap[$id++] = $adminUser;

        foreach ($userArray as $userJson) {
            $user = new User();
            $user->setUsername($userJson['username']);
            $user->setPassword($this->hasher->hashPassword($user, $userJson['password']));
            $user->setEmail($userJson['email']);
            $user->setPicture($userJson['picture']);
            $manager->persist($user);

            $userMap[$id] = $user;
            $id++;
        }

        $manager->flush();

        $fileContents = file_get_contents(self::DATA_DIR . '/trip.json');
        $tripArray = json_decode($fileContents, true);

        $tripMap = [];
        $id = 1;

        foreach ($tripArray as $tripJson) {
            $trip = new Trip();
            $trip->setTitle($tripJson['title']);
            $trip->setDescription($tripJson['description']);
            $trip->setDestination($tripJson['destination']);
            $trip->setStartDate(new \DateTime($tripJson['startDate']));
            $trip->setEndDate(new \DateTime($tripJson['endDate']));
            $trip->setImage($tripJson['image']);
            $randomUserKey = array_rand($userMap);
            $trip->setUser($userMap[$randomUserKey]);
            $manager->persist($trip);
            $tripMap[$id] = $trip;
            $id++;
        }

        $manager->flush();

        $fileContents = file_get_contents(self::DATA_DIR . '/stop.json');
        $stopArray = json_decode($fileContents, true);

        $id = 1;

        foreach ($stopArray as $stopJson) {
            $stop = new Stop();
            $stop->setTitle($stopJson['title']);
            $stop->setLocation($stopJson['location']);
            $stop->setContent($stopJson['content']);
            $stop->setDate(new \DateTime($stopJson['date']));
            $stop->setImage($stopJson['image']);
            $stop->setTrip($tripMap[$stopJson['trip']]);
            $manager->persist($stop);

            $stopMap[$id] = $stop;
            $id++;
        }

        $manager->flush();

        $fileContents = file_get_contents(self::DATA_DIR . '/comment.json');
        $commentArray = json_decode($fileContents, true);

        $id = 1;

        foreach ($commentArray as $commentJson) {
            $comment = new Comment();
            $comment->setContent($commentJson['content']);
            $comment->setCreatedAt(new \DateTime($commentJson['created_at']));
            $comment->setStop($stopMap[$commentJson['stop']]);
            $comment->setUser($userMap[random_int(1, count($userMap))]);
            $manager->persist($comment);
            $commentMap[$id] = $comment;
            $id++;
        }

        $manager->flush();

    }
}
