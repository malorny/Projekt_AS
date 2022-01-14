<?php

namespace App\Repository;

use App\Entity\Lake;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lake|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lake|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lake[]    findAll()
 * @method Lake[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LakeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lake::class);
    }

    // /**
    //  * @return Lake[] Returns an array of Lake objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lake
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
