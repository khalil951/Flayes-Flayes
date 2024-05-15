<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatRepository;

#[ORM\Entity(repositoryClass: CatRepository::class)]
#[ORM\Table(name: "category")]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idcat", type: "integer", nullable: false)]
    private int $idcat;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $type;

    #[ORM\Column(length: 255)]
    private string $target;

    public function getIdcat(): int
    {
        return $this->idcat;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(string $target): void
    {
        $this->target = $target;
    }
}
