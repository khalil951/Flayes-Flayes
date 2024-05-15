<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Custom repository method to search for an event by id.
     * @param int $id The id of the event to find.
     * @return Event|null Returns the event or null if not found.
     */
    public function search1($id)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.idevent = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function searchAll($query)
{
    return $this->createQueryBuilder('s')
        ->where('s.name LIKE :query')
        ->orWhere('s.idevent LIKE :query')

        ->orWhere('s.description LIKE :query')
        ->orWhere('s.location LIKE :query')
        ->orWhere('s.date LIKE :query') // Ensure this is a viable condition, might need formatting
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}

public function descevent()
{
    return $this->createQueryBuilder('e')
        ->orderBy('e.name', 'DESC')
        ->getQuery()
        ->getResult();
}
    
}
