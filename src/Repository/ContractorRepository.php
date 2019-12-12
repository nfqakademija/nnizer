<?php

namespace App\Repository;

use App\Entity\Contractor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

/**
 * @method Contractor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contractor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contractor[]    findAll()
 * @method Contractor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractorRepository extends ServiceEntityRepository
{
    /**
     * @var
     */
    private $logger;

    /**
     * ContractorRepository constructor.
     * @param ManagerRegistry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        $this->logger = $logger;
        parent::__construct($registry, Contractor::class);
    }

    /**
     * @param string $key
     * @return Contractor|null
     * @throws NonUniqueResultException
     */
    public function findOneByKey(string $key): ?Contractor
    {
        return $this->createQueryBuilder('c')
            ->where('c.verificationKey = :key')
            ->setParameter('key', $key)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Contractor $contractor
     */
    public function save(Contractor $contractor): void
    {
        try {
            $this->_em->persist($contractor);
            $this->_em->flush();
        } catch (ORMException $e) {
            $this->logger->error($e->getMessage());
        }
    }

    // /**
    //  * @return Contractor[] Returns an array of Contractor objects
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
    public function findOneBySomeField($value): ?Contractor
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
