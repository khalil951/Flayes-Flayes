<?php

namespace App\Entity;
use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Column]
     #[ORM\Id]
     #[ORM\GeneratedValue]
    private ?int $idRec = null;

    

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message : "This field should not be blank.")]
    #[Assert\Length(
       min : 7,
       minMessage : "The field should be at least {{ limit }} characters long."
  )]
  #[Assert\Regex(
       pattern : "/^[a-zA-Z]+$/",
       message : "Only letters are allowed in this field."
  )]
    private ?string $object = null;

    #[ORM\Column(length: 500)]
    private ?string $type = null;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message : "This field should not be blank.")]
    private ?string $description =null;

    

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 500)]
    private ?string $etat = null;

     #[ORM\ManyToOne(inversedBy: 'reclamations')]
     private $idUser;
     private ?User $user = null;

    public function getIdRec(): ?int
    {
        return $this->idRec;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): static
    {
        $this->object = $object;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

   


}
