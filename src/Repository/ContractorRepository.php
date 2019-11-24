<?php

namespace App\Repository;

use App\Entity\Contractor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Contractor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contractor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contractor[]    findAll()
 * @method Contractor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
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
