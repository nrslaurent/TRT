<?php

namespace App\Repository;

use App\Entity\Jobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Jobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jobs[]    findAll()
 * @method Jobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jobs::class);
    }

    // /**
    //  * @return Jobs[] Returns an array of Jobs objects
    //  */

    public function jobsSearch($value)
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->Where('j.title LIKE :val')
            ->orWhere('j.description LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('j.title', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function jobsByIdDesc()
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->orderBy('j.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Jobs
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
