<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket", indexes={@ORM\Index(name="Iduser", columns={"Iduser"}), @ORM\Index(name="Idevent", columns={"Idevent"})})
 * @ORM\Entity
 */
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idticket = null;

    #[ORM\Column(length: 255)]
    private ?string $qrcode = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tickets")
     * @ORM\JoinColumn(name="Iduser", referencedColumnName="id")
     */
    private ?User $iduser = null;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="tickets")
     * @ORM\JoinColumn(name="Idevent", referencedColumnName="idevent")
     */
    private ?Event $idevent = null;

    public function getIdticket(): ?int
    {
        return $this->idticket;
    }

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(string $qrcode): static
    {
        $this->qrcode = $qrcode;
        return $this;
    }

    public function getIdevent(): ?Event
    {
        return $this->idevent;
    }

    public function setIdevent(?Event $idevent): static
    {
        $this->idevent = $idevent;
        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;
        return $this;
    }
}
