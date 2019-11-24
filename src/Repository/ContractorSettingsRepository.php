<?php

namespace App\Repository;

use App\Entity\ContractorSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;

/**
 * @method ContractorSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContractorSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContractorSettings[]    findAll()
 * @method ContractorSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractorSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractorSettings::class);
    }

    public function save(ContractorSettings $contractorSettings)
    {
        try {
            $this->_em->persist($contractorSettings);
            $this->_em->flush();
        } catch (ORMException $e) {
        }
    }


    // /**
    //  * @return ContractorSettings[] Returns an array of ContractorSettings objects
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
    public function findOneBySomeField($value): ?ContractorSettings
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
