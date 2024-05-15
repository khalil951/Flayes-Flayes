<?php

namespace App\Entity;

use App\Repository\FundingRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: FundingRepository::class)]
#[ORM\Table(name: "funding")]
class Funding
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id" , type: "integer" , nullable: "false")]
    private ?int $id = null;


    #[ORM\Column(length: 255)]

    private ?string $type = null;


    #[ORM\Column]

    private ?float $attribute1= null;

    #[ORM\Column(name: "attribute2", type: "float", precision: 10, scale: 0, nullable: true)]

    private ?float $attribute2;

    #[ORM\Column(name: "attribute3", type: "float", precision: 10, scale: 0, nullable: true)]

    private ?float $attribute3;

    #[ORM\Column(name: "textAttribute", type: "string",  length:255, nullable:false)]

    private ?string $textattribute;

    #[ORM\Column(name: "score", type: "float", precision: 10, scale: 0, nullable: true)]
    private ?float $score;
    public function getId(): ?int
    {
        return $this->id;
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

    public function getAttribute1(): ?float
    {
        return $this->attribute1;
    }

    public function setAttribute1(?float $attribute1): static
    {
        $this->attribute1 = $attribute1;

        return $this;
    }

    public function getAttribute2(): ?float
    {
        return $this->attribute2;
    }

    public function setAttribute2(?float $attribute2): static
    {
        $this->attribute2 = $attribute2;

        return $this;
    }

    public function getAttribute3(): ?float
    {
        return $this->attribute3;
    }

    public function setAttribute3(?float $attribute3): static
    {
        $this->attribute3 = $attribute3;

        return $this;
    }

    public function getTextattribute(): ?string
    {
        return $this->textattribute;
    }

    public function setTextattribute(string $textattribute): static
    {
        $this->textattribute = $textattribute;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): static
    {
        $this->score = $score;

        return $this;
    }


}
