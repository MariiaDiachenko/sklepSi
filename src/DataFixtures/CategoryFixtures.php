<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

  /**
  * Category Fixtures class
  */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $category = new Category();
            $category->setName($this->faker->unique()->word());
            $this->manager->persist($category);
        }
        $this->manager->flush();
    }
}
