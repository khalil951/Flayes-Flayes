<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ORM\Table(name: "ticket")]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idticket = null;

    #[ORM\Column(length: 255)]
    private ?string $qrcode = null;

    // Relationship to User with cascade operations
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "tickets", cascade: ["persist"])]
    #[ORM\JoinColumn(name: "Iduser", referencedColumnName: "id", nullable: false)]
    private User $iduser;

    // Relationship to Event with cascade operations
    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: "tickets", cascade: ["persist"])]
    #[ORM\JoinColumn(name: "Idevent", referencedColumnName: "Idevent", nullable: false)]
    private Event $idevent;
   
    // Getters and Setters
    public function getIdticket(): ?int
    {
        return $this->idticket;
    }

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(string $qrcode): self
    {
        $this->qrcode = $qrcode;
        return $this;
    }

    public function getEvent(): Event
    {
        return $this->idevent;
    }

    public function setEvent(Event $idevent): self
    {
        $this->idevent = $idevent;
        return $this;
    }

    public function getUser(): User
    {
        return $this->iduser;
    }

    public function setUser(User $iduser): self
    {
        $this->iduser = $iduser;
        return $this;
    }
}
