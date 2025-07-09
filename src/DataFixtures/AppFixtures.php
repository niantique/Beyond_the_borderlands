<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Stop;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const DATA_DIR = __DIR__ . '/data';
    public function load(ObjectManager $manager): void
    {
        $fileContents = file_get_contents(self::DATA_DIR . '/user.json');
        $userArray = json_decode($fileContents, true);

        $userMap = [];
        $id = 1;
        foreach ($userArray as $userJson) {
            $user = new User();
            $user->setUsername($userJson['username']);
            $user->setPassword($userJson['password']);
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
            $trip->setUser($userMap[$tripJson['user']]);
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
