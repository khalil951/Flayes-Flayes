<?php

namespace App\Repository;

use App\Entity\Ticket;  // Change this to use the Ticket entity
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository  // Rename the class appropriately
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class); // Change the class reference to Ticket
    }

    public function findByEventAndUser(int $eventId, int $userId) {
        return $this->findOneBy([
            'idevent' => $eventId,
            'iduser' => $userId
        ]);
    }
    

    // Add custom repository methods if needed for Ticket entities
}
