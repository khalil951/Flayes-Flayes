<?php

namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;


 #[ORM\Entity(repositoryClass: UserRepository::class)]
 
class User
{
    
     
     #[ORM\Column]
     #[ORM\Id]
     #[ORM\GeneratedValue]
    private ?int $id = null;

    
     #[ORM\Column(length: 500)]
     private ?string $name = null;


    #[ORM\Column(length: 500)]
     private ?string $email = null;


    #[ORM\Column(length: 500)]
    private ?string $tel = null;


    #[ORM\Column(length: 500)]
    private ?string $password = null;


    #[ORM\Column(length: 500)]
    private ?string $roles = null;


    #[ORM\Column(length: 500)]
    private ?string $imageName = null;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): static
    {
        $this->imageName = $imageName;

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


}
