<?php

namespace App\Repository;

use App\Entity\CoverPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CoverPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoverPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoverPhoto[]    findAll()
 * @method CoverPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoverPhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoverPhoto::class);
    }

    // /**
    //  * @return CoverPhoto[] Returns an array of CoverPhoto objects
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
    public function findOneBySomeField($value): ?CoverPhoto
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
