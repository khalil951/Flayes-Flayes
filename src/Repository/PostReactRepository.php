<?php

namespace App\Repository;

use App\Entity\PostReact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostReact>
 *
 * @method PostReact|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostReact|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostReact[]    findAll()
 * @method PostReact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostReactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostReact::class);
    }

//    /**
//     * @return PostReact[] Returns an array of PostReact objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostReact
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
