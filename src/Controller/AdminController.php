<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends EasyAdminController
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * AdminController constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $entityManager;
        $this->encoder = $passwordEncoder;
    }

    /**
     * @param User $user
     */
    public function persistUserEntity(User $user)
    {
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $encodedPassword = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($encodedPassword);
        $user->eraseCredentials();
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param Contractor $contractor
     */
    public function persistContractorEntity(Contractor $contractor)
    {
        $encodedPassword = $this->encoder->encodePassword($contractor, $contractor->getPlainPassword());
        $contractor->setPassword($encodedPassword);
        $contractor->eraseCredentials();
        $this->em->persist($contractor);
        $this->em->flush();
    }

    /**
     * @param Contractor $contractor
     */
    public function updateContractorEntity(Contractor $contractor)
    {
        if (method_exists($contractor, 'setPassword')) {
            $contractor->setPassword($this->encoder->encodePassword($contractor, $contractor->getPlainPassword()));
        }
        $contractor->eraseCredentials();

        parent::updateEntity($contractor);
    }

    /**
     * @param User $user
     */
    public function updateUserEntity(User $user)
    {
        if (method_exists($user, 'setPassword')) {
            $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
        }
        $user->eraseCredentials();

        parent::updateEntity($user);
    }
}
