<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
* RoleFixtures class
*/
class RoleFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
    * @inheritdoc
    */
    public function loadData(ObjectManager $manager): void
    {
        $admin = $manager->getRepository(User::class)->findBy(['username' => 'admin'])[0];

        $role = new Role();
        $role->setRole(User::ROLE_ADMIN);
        $role->setUser($admin);

        $this->manager->persist($role);

        $this->manager->flush();
    }

    /**
    * @inheritdoc
    */
    public function getDependencies()
    {
        return [
        UserFixtures::class,
        ];
    }
}
