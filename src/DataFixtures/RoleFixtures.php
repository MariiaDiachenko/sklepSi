<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\ShopRepository;
use App\Repository\RoleRepository;
use App\DataFixtures\ShopFixtures;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RoleFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
      $admin = $manager->getRepository(User::class)->findBy(['username' => 'admin'])[0];

      $role = new Role();
      $role->setRole(User::ROLE_ADMIN);
      $role->setUser($admin);

      $this->manager->persist($role);

      $this->manager->flush();
    }

    public function getDependencies()
    {
      return [
        UserFixtures::class,
      ];
    }
}
