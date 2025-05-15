<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $faker = Factory::create();

        // for ($i = 1; $i <= 250_000; $i++) {
        //     $product = new Product();
        //     $product->setName($faker->words(3, true));
        //     $product->setPrice($faker->numberBetween(1, 1000));
        //     $manager->persist($product);

        //     if ($i % 1000 === 0) {
        //         $manager->flush();
        //         $manager->clear();
        //     }
        // }

        // $manager->flush();


        // $categories = [];
        // for ($i = 0; $i < 100; $i++) {
        //     $category = new Category();
        //     $category->setName("Category $i");
        //     $manager->persist($category);
        //     $categories[] = $category;
        // }

        // $manager->flush();
        // $manager->clear();

        $categories = $manager->getRepository(Category::class)->findAll();

        for ($i = 1; $i <= 1000000; $i++) {
            $product = new Product();
            $product->setName("Product $i");
            $product->setPrice(rand(1, 1000));
            $product->setCategory($categories[array_rand($categories)]);
            $manager->persist($product);

            if ($i % 100000 === 0) {
                $manager->flush();
                $manager->clear();
                echo $i . " ";
                $categories = $manager->getRepository(Category::class)->findAll();
            }
        }

        $manager->flush();
    }
}
