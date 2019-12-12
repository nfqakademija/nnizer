<?php

namespace App\Repository;

use App\Entity\Contractor;
use App\Entity\Reservation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Tests\Fixtures\includes\HotPath\P1;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    /**
     * @var
     */
    private $logger;

    /**
     * ReservationRepository constructor.
     * @param ManagerRegistry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        $this->logger = $logger;
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @param Contractor $contractor
     * @param DateTime $from
     * @param DateTime $to
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findByDateInterval(Contractor $contractor, DateTime $from, DateTime $to): array
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
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findByInComplete(DateTime $now): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.isVerified = 1')
            ->andWhere('c.isCompleted = 0')
            ->andWhere('c.visitDate < :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Reservation $reservation
     */
    public function save(Reservation $reservation): void
    {
        try {
            $this->_em->persist($reservation);
            $this->_em->flush();
        } catch (ORMException $e) {
            $this->logger->error($e->getMessage());
        }
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
