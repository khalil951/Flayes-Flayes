<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: "project" )]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id" , type: "integer" , nullable: "false")]
    private ?int $id = null;

    #[ORM\Column(name: "name" , type: "string" , length:255 , nullable: "false")]
    private ?string $name = null;


    #[ORM\Column(name: "description" , type: "text" , length:65535 , nullable: "false")]
    private ?string $description = null;


    #[ORM\Column(name: "type" , type: "string" , length:255 , nullable: "false")]
    private ?string $type = null;


    #[ORM\Column(name: "status" , type: "boolean" , nullable: "false")]
    private ?string $status = null;


    #[ORM\Column(name: "added_date" , type: "date" , nullable: "false")]
    private ?\DateTime $addedDate = null;


    #[ORM\Column(name: "end_date" , type: "date" , nullable: "false")]
    private ?\DateTime $endDate = null;

    #[ORM\Column(name: "user_status", type: "integer", nullable: true)]
    private ?int $userStatus;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private ?User $user;
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->addedDate;
    }

    public function setAddedDate(\DateTimeInterface $addedDate): static
    {
        $this->addedDate = $addedDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUserStatus(): ?int
    {
        return $this->userStatus;
    }

    public function setUserStatus(?int $userStatus): static
    {
        $this->userStatus = $userStatus;

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


}
