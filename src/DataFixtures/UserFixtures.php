<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
* User Fixtures class
*/
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

    /**
    * @inheritdoc
    */
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

        for ($i = 0; $i < 10; ++$i) {
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
