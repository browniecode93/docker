<?php

namespace App\Repository;

use App\Entity\Api;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @method Api|null find($id, $lockMode = null, $lockVersion = null)
 * @method Api|null findOneBy(array $criteria, array $orderBy = null)
 * @method Api[]    findAll()
 * @method Api[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Api::class);
    }

    public function findAllSuccessfulSent()
    {
        $qb = $this->createQueryBuilder('successful_sms');
        return $qb
            ->select('count(successful_sms.id)')
            ->where('successful_sms.api_status = 200')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findApiUsage($api_call)
    {
        $qb = $this->createQueryBuilder('api_usage');
        return $qb
            ->select('count(api_usage.id)')
            ->where('api_usage.api_call LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$api_call.'%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findApiFailure($api_call)
    {
        $qb = $this->createQueryBuilder('api_failure');
        return $qb
            ->select('count(api_failure.id)')
            ->where('api_failure.api_call LIKE :searchTerm AND api_failure.api_status = 500')
            ->setParameter('searchTerm', '%'.$api_call.'%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTopTen()
    {
        $qb = $this->createQueryBuilder('top_ten');
        return $qb
            ->select('top_ten.number, count(top_ten.number) as mycount')
            ->where('top_ten.api_status = 200 and top_ten.number IS NOT NULL')
            ->groupBy('top_ten.number')
            ->orderBy('mycount', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();
    }



    // /**
    //  * @return Api[] Returns an array of Api objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Api
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
