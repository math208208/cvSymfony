<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $faker = Factory::create('fr_FR'); //Faker donnnées aléatoire

        // for ($i = 0; $i < 50; $i++) {
        //     $user = new User();
        //     $user->setNom($faker->lastName);
        //     $user->setPrenom($faker->firstName);
        //     $user->setProfession($faker->jobTitle);
        //     $user->setDescription($faker->paragraph);
        //     $user->setEmail($faker->unique()->email);
        //     $user->setTelephone($faker->phoneNumber);
        //     $user->setLinkdin('https://linkedin.com/in/'.$faker->userName);
        //     $user->setGithub('https://github.com/'.$faker->userName);

        //     $manager->persist($user);
        // }
        // $manager->flush();

        // Admin (accès complet)
        // User normal (accès restreint)
    }
}
