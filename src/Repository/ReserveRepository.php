<?php

namespace App\Repository;

use App\Entity\Reserve;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reserve|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reserve|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reserve[]    findAll()
 * @method Reserve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReserveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserve::class);
    }

    public function anyReservation(int $lakeId, \DateTimeInterface $begin, \DateTimeInterface $end)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT r
            FROM App\Entity\Reserve r
            WHERE r.lake_id = :lakeId AND ((r.begin_date <= :beginDate AND r.end_date >= :endDate) OR (r.end_date >= :beginDate AND r.begin_date <= :endDate))
        ')
        ->setParameter('lakeId', $lakeId)
        ->setParameter('beginDate', $begin)
        ->setParameter('endDate', $end);

        return $query->getResult();
    }

    // /**
    //  * @return Reserve[] Returns an array of Reserve objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reserve
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
