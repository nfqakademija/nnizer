<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFactory
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AdminFactory constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $name
     * @return User
     */
    public function createAdmin(
        string $email,
        string $password,
        string $name
    ): User {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setName($name);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        return $user;
    }
}
