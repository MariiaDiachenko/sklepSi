<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Shop;
use App\Repository\ShopRepository;
use App\Repository\CategoryRepository;

class ShopFixtures extends AbstractBaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $shop = new Shop();
        $shop->setName($this->faker->unique()->word());
        $shop->setAddress($this->faker->unique()->streetAddress());
        $shop->setPhone($this->faker->unique()->e164PhoneNumber);
        $shop->setEmail($this->faker->unique()->safeEmail);
        $shop->setBankAccount('29 9999 9999 9999 9999 9999 9999');
        $this->manager->persist($shop);
      $this->manager->flush();
    }
}
