<?php

namespace App\Repository;

use App\Entity\RenglonHistorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RenglonHistorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method RenglonHistorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method RenglonHistorial[]    findAll()
 * @method RenglonHistorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RenglonHistorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RenglonHistorial::class);
    }

    // /**
    //  * @return RenglonHistorial[] Returns an array of RenglonHistorial objects
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
    public function findOneBySomeField($value): ?RenglonHistorial
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
