<?php

namespace App\Repository;

use App\Entity\Contractor;
use App\Entity\LostPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method LostPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method LostPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method LostPassword[]    findAll()
 * @method LostPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LostPasswordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LostPassword::class);
    }

    /**
     * @param Contractor $contractor
     * @return LostPassword|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findActiveEntry(Contractor $contractor): ?LostPassword
    {
        $now = new \DateTime('now');
        return $this->createQueryBuilder('lp')
            ->andWhere('lp.contractor = :contractor')
            ->setParameters(['contractor' => $contractor])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param String $key
     * @return LostPassword|null
     * @throws \Exception
     */
    public function findActiveEntryByKey(String $key): ?LostPassword
    {
        $now = new \DateTime('now');
        return $this->createQueryBuilder('lp')
            ->andWhere('lp.resetKey = :key')
            ->andWhere('lp.expiresAt > :now')
            ->setParameters(['now' => $now->modify('-60 minutes'), 'key' => $key])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param LostPassword $lostPassword
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(LostPassword $lostPassword)
    {
        $this->_em->remove($lostPassword);
        $this->_em->flush();
    }

    /**
     * @param LostPassword $lostPassword
     * @throws ORMException
     */
    public function save(LostPassword $lostPassword)
    {
        $this->_em->persist($lostPassword);
        $this->_em->flush();
    }
}
