<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends AbstractBaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
      for ($i=0; $i < 10; $i++) {
        $category = new Category();
        $category->setName($this->faker->unique()->word());
        $this->manager->persist($category);
      }
      $this->manager->flush();
    }
}
