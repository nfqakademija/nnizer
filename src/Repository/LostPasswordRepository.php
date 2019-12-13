<?php

namespace App\Repository;

use App\Entity\Contractor;
use App\Entity\LostPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
     * @return LostPassword[]
     * @throws \Exception
     */
    public function findAllActiveEntries(): array
    {
        $now = new \DateTime('now');
        return $this->createQueryBuilder('lp')
            ->andWhere('lp.expiresAt > :now')
            ->setParameter('now', $now->modify('-60 minutes'))
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return LostPassword[] Returns an array of LostPassword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LostPassword
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
