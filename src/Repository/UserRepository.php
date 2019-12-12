<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        $this->logger = $logger;
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $email
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findByEmail($email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        try {
            $this->_em->persist($user);
            $this->_em->flush();
        } catch (ORMException $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
