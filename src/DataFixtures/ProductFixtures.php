<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;
use App\Repository\ShopRepository;
use App\Repository\CategoryRepository;

class ProductFixtures extends AbstractBaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
      for ($i=0; $i < 23; $i++) {
        $product = new Product();

        $product->setName($this->faker->unique()->word());
        $product->setPrice($this->faker->unique()->numberBetween(1000,30000));
        $product->setImg('b4fc1626265d3a6337783739ba39fc8b9c618275950029d26c73c248e95eadbb.jpeg');
        $product->setDescription($this->faker->paragraph($nbSentences = 3, $variableNbSentences = true));
        $product->setIsNew($this->faker->boolean(25));
        $product->setIsRecommended($this->faker->boolean(25));
        $this->manager->persist($product);
      }
      $this->manager->flush();
    }
}
