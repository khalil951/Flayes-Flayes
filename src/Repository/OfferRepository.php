<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function findOffersImade($value): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.user =:val')
//            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findOffersIgot($value): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.reciever =:val')
            ->andWhere('o.status > 1')
            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }


//    /**
//     * @return OfferFunding[] Returns an array of OfferFunding objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OfferFunding
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findFilteredOffers($dateFrom, $dateTo, $status): array
    {
        $qb = $this->createQueryBuilder('o');

        if ($dateFrom) {
            $qb->andWhere('o.dateCreated >= :dateFrom')
                ->setParameter('dateFrom', $dateFrom);
        }

        if ($dateTo) {
            $qb->andWhere('o.dateCreated <= :dateTo')
                ->setParameter('dateTo', $dateTo);
        }

        if ($status) {
            $qb->andWhere('o.status = :status')
                ->setParameter('status', $status);
        }

        return $qb->getQuery()->getResult();
    }
}
