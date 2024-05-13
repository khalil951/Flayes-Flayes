<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[ORM\Table(name: "Offer")]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id" , type: "integer" , nullable: "false")]
    private ?int $id= null;


    #[ORM\Column(name: "title", type: "string", length:255, nullable:false)]
    private ?string $title= null;


    #[ORM\Column(name:"description", type:"text", length:65535, nullable:false)]
    private ?string $description = null;


    #[ORM\Column(name:"status" , type: "integer" , nullable: false)]
    private ?int $status = null;


    #[ORM\Column(name:"date_created", type:"date", nullable:false)]
    private ?\DateTime $dateCreated;

    #[ORM\OneToOne(targetEntity: Funding::class)]
    #[ORM\JoinColumn(name: "funding_id", referencedColumnName: "id")]
    private ?Funding $funding;
    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(name: "project_id", referencedColumnName: "id")]
    private ?Project $project;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private ?User $user;

    #[ORM\ManyToOne]
    private ?User $reciever = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getFunding(): ?Funding
    {
        return $this->funding;
    }

    public function setFunding(?Funding $funding): static
    {
        $this->funding = $funding;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getRecieverId(): ?int
    {
        return $this->reciever_id;
    }

    public function setRecieverId(?int $reciever_id): static
    {
        $this->reciever_id = $reciever_id;

        return $this;
    }

    public function getReciever(): ?User
    {
        return $this->reciever;
    }

    public function setReciever(?User $reciever): static
    {
        $this->reciever = $reciever;

        return $this;
    }


}
