<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

            // Creation of a french locale for the faker
            $faker = Faker\Factory::create("fr_FR");
    
            // Use of a populator
            $populator = new \Faker\ORM\Doctrine\Populator($faker, $manager);
    
    
            // creation of the category class to populate
            $populator->addEntity(Article::class, 50,
            [
                "title" => function () use ($faker) {
                    return $faker->text(15);
                },
                "status" => function () use ($faker) {
                    return $faker->numberBetween(0,1);
                },
                "text" => function () use ($faker) {
                    return $faker->text(800);
                },
                "author" => function () use ($faker) {
                    return $faker->name();
                }
            ]
            );
    
            // We execute the populator to add the data locally
            $insertedItems = $populator->execute();

        $manager->flush();
    }
}
