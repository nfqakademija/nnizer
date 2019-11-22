<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @param string $contractor
     * @param DateTime $from
     * @param DateTime $to
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findByDateInterval(string $contractor, DateTime $from, DateTime $to): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.contractor = :contractor')
            ->andWhere('c.visitDate > :from')
            ->andWhere('c.visitDate < :to')
            ->setParameters(['contractor' => $contractor, 'from' => $from, 'to' => $to])
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param DateTime $now
     * @return array
     */
    public function findByInComplete(DateTime $now): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isVerified = true')
            ->andWhere('c.isCompleted = false')
            ->andWhere('c.visitDate < :now')
            ->setParameters(['now' => $now])
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
