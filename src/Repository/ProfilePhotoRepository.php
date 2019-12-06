<?php

namespace App\Repository;

use App\Entity\ProfilePhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProfilePhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilePhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilePhoto[]    findAll()
 * @method ProfilePhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilePhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilePhoto::class);
    }

    // /**
    //  * @return ProfilePhoto[] Returns an array of ProfilePhoto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfilePhoto
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
