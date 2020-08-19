<?php

namespace App\Repository;

use App\Entity\ProfilDeSorti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfilDeSorti|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilDeSorti|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilDeSorti[]    findAll()
 * @method ProfilDeSorti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilDeSortiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilDeSorti::class);
    }

    // /**
    //  * @return ProfilDeSorti[] Returns an array of ProfilDeSorti objects
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
    public function findOneBySomeField($value): ?ProfilDeSorti
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
