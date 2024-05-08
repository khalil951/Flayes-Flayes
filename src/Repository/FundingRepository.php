<?php

namespace App\Repository;

use App\Entity\Funding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Funding>
 *
 * @method Funding|null find($id, $lockMode = null, $lockVersion = null)
 * @method Funding|null findOneBy(array $criteria, array $orderBy = null)
 * @method Funding[]    findAll()
 * @method Funding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FundingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Funding::class);
    }

//    /**
//     * @return Funding[] Returns an array of Funding objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Funding
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
