<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enumerator\UserRoles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['users'];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager, $this->encoder);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     */
    private function loadUsers(ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $names = ['nfq', 'dominykas', 'kornelijus', 'migle', 'tadas'];
        foreach ($names as $name) {
            $user = new User();
            $user->setEmail($name . '@' . $name . '.com');
            $user->setRoles([UserRoles::ADMIN]);
            $user->setPassword($encoder->encodePassword($user, $name));
            $user->setName($name);
            $manager->persist($user);
        }
    }
}
