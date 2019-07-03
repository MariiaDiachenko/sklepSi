<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Shop;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
* Product Fixtures class
*/
class ProductFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
    * @inheritdoc
    */
    public function loadData(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findBy([], null, 10);
        $shops = $manager->getRepository(Shop::class)->findBy([], null, 10);

        for ($i = 0; $i < 23; ++$i) {
            $product = new Product();

            $product->setName($this->faker->unique()->word());
            $product->setPrice($this->faker->unique()->numberBetween(1000, 30000));
            $product->setImg('b4fc1626265d3a6337783739ba39fc8b9c618275950029d26c73c248e95eadbb.jpeg');
            $product->setDescription($this->faker->paragraph(1, true));
            $product->setCategory($categories[random_int(0, 9)]);
            $product->setShop($shops[0]);
            $product->setIsNew($this->faker->boolean(25));
            $product->setIsRecommended($this->faker->boolean(25));
            $this->manager->persist($product);
        }
        $this->manager->flush();
    }

    /**
    * @inheritdoc
    */
    public function getDependencies()
    {
        return [
        CategoryFixtures::class,
        ShopFixtures::class,
        ];
    }
}
