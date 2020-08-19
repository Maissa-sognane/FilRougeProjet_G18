<?php

namespace App\Repository;

use App\Entity\FileDeDiscussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FileDeDiscussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileDeDiscussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileDeDiscussion[]    findAll()
 * @method FileDeDiscussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileDeDiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileDeDiscussion::class);
    }

    // /**
    //  * @return FileDeDiscussion[] Returns an array of FileDeDiscussion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FileDeDiscussion
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
