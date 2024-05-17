<?php

namespace App\Entity;

use App\Repository\EventRepository;  // Import the repository class

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: "event")]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "Idevent", type: "integer", nullable: false)]
    private ?int $idevent = null; // Corrected property name

    #[ORM\Column(name:"name", type:"string", length:255, nullable:false)]
    private ?string $name = null;

    #[ORM\Column(name:"date", type:"string", length:255, nullable:false)]
    private ?string $date = null;

    #[ORM\Column(name:"description", type:"string", length:255, nullable:false)]
    private ?string $description = null;

    #[ORM\Column(name:"location", type:"string", length:255, nullable:false)]
    private ?string $location = null;

    #[ORM\Column(name:"image", type:"string", length:255, nullable:false)]
    private ?string $image = null;

    #[ORM\Column(name:"qrcode", type:"string", length:255, nullable:false)]
    private ?string $qrcode = null;

    #[ManyToOne]
    #[JoinColumn(name: "idcat", referencedColumnName: "Idcat")]
    private ?Category $idcat ;


    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
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

    public function getIdcat(): ?Category
    {
        return $this->idcat;
    }

    public function setIdcat(?Category $idcat): static
    {
        $this->idcat = $idcat;
        return $this;
    }
}
