<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Repository\ShopRepository;
use App\Repository\ProductRepository;
use App\DataFixtures\ShopFixtures;
use App\DataFixtures\CategoryFixtures;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends AbstractBaseFixtures
{

      /**
    * Password encoder.
    *
    * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
    */
    private $passwordEncoder;

    /**
    * UserFixtures constructor.
    *
    * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder Password encoder
    */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
      $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager): void
    {
      $user = new User();

      $user->setUsername('admin');
      $user->setPassword($this->passwordEncoder->encodePassword(
        $user,
        'admin'
      ));
      $user->setName($this->faker->unique()->firstName);
      $user->setSurname($this->faker->unique()->lastName);
      $user->setEmail($this->faker->unique()->safeEmail);
      $user->setPhone($this->faker->unique()->e164PhoneNumber);

      $this->manager->persist($user);

      for ($i=0; $i < 10; $i++) {
        $user = new User();

        $user->setUsername($this->faker->unique()->name);
        $user->setPassword($this->passwordEncoder->encodePassword(
          $user,
          'admin'
        ));
        $user->setName($this->faker->unique()->firstName);
        $user->setSurname($this->faker->unique()->lastName);
        $user->setEmail($this->faker->unique()->safeEmail);
        $user->setPhone($this->faker->unique()->e164PhoneNumber);

        $this->manager->persist($user);
      }
      $this->manager->flush();
    }
}
