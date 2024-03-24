<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity
 */
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"Idevent", type:"integer", nullable:false)]
    private ?int $idevent = null;

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

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="idcat", referencedColumnName="Idcat")
     */
    private ?Category $idcat = null;

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
