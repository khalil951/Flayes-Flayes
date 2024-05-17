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

    #[ORM\Column(length: 200)]
    private ?string $response = null;

     #[ORM\ManyToOne(inversedBy: 'reclamations')]
     private ?User $user = null;

     #[ORM\Column]
    private ?int $id_user = null;
    
    #[ORM\Column]
    private ?int $user_id = null;

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
    public function getRespons(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): static
    {
        $this->response = $response;

        return $this;
    }
  /*
    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }
    */

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    
    private $totalRatings;

    
    private $totalStars;

    public function __construct()
    {
        // Initialize ratings properties
        $this->totalRatings = 0;
        $this->totalStars = 0;
    }

    public function getTotalRatings(): int
    {
        return $this->totalRatings;
    }

    public function setTotalRatings(int $totalRatings): self
    {
        $this->totalRatings = $totalRatings;
        return $this;
    }

    public function getTotalStars(): int
    {
        return $this->totalStars;
    }

    public function setTotalStars(int $totalStars): self
    {
        $this->totalStars = $totalStars;
        return $this;
    }

    public function addRating(int $stars): self
    {
        $this->totalRatings++;
        $this->totalStars += $stars;
        return $this;
    }

    public function getAverageRating(): float
    {
        if ($this->totalRatings > 0) {
            return $this->totalStars / $this->totalRatings;
        }
        return 0;
    }


}
