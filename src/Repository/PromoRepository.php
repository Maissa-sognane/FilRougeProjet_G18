<?php

namespace App\Repository;

use App\Entity\Promo;
use App\Entity\Groupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promo[]    findAll()
 * @method Promo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promo::class);
    }

    // /**
    //  * @return Promo[] Returns an array of Promo objects
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
    public function findOneBySomeField($value): ?Promo
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByTypeJoinedToGroup($type)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p, g
        FROM App\Entity\Promo p
        INNER JOIN p.groupe g
        WHERE g.type = :type'
        )->setParameter('type', $type);
        return $query->getResult();
    }

    public function findOneByTypeJoinedToApprenantAttente($statut)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT p, g, a
        FROM App\Entity\Promo p 
        INNER JOIN p.groupe g
        INNER JOIN g.apprenant a
        WHERE a.islogging = :statut'
        )->setParameter('statut', $statut);
        return $query->getResult();
    }


    public function findOneByTypeJoinGroupPrincipal($type, $id)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p, g
        FROM App\Entity\Promo p
        INNER JOIN p.groupe g
        WHERE g.type = :type AND p.id = :id'
        );
        $query->setParameter('type', $type);
        $query->setParameter('id', $id);
        return $query->getResult();
    }


    public function findOneByApprenantAttente($statut, $id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT p, g, a
        FROM App\Entity\Promo p 
        INNER JOIN p.groupe g
        INNER JOIN g.apprenant a
        WHERE a.islogging = :statut AND p.id = :id'
        );
        $query->setParameter('statut', $statut);
        $query->setParameter('id', $id);
        return $query->getResult();
    }

    public function findOnePromoGroupe($groupe, $id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT p, g
        FROM App\Entity\Promo p 
        INNER JOIN p.groupe g
        WHERE g.id = :groupe AND p.id = :id'
        );
        $query->setParameter('groupe', $groupe);
        $query->setParameter('id', $id);
        return $query->getResult();
    }

    public function findOneProm($id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT p,f
        FROM App\Entity\Promo p 
        INNER JOIN p.formateur f
        WHERE  p.id = :id'
        );
        $query->setParameter('id', $id);
        return $query->getResult();
    }


    /*
   public function findOneBySomeField($value): ?Promo
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
