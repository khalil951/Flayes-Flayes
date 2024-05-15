<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Funding
 *
 * @ORM\Table(name="funding")
 * @ORM\Entity
 */
class Funding
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var float|null
     *
     * @ORM\Column(name="attribute1", type="float", precision=10, scale=0, nullable=true)
     */
    private $attribute1;

    /**
     * @var float|null
     *
     * @ORM\Column(name="attribute2", type="float", precision=10, scale=0, nullable=true)
     */
    private $attribute2;

    /**
     * @var float|null
     *
     * @ORM\Column(name="attribute3", type="float", precision=10, scale=0, nullable=true)
     */
    private $attribute3;

    /**
     * @var string
     *
     * @ORM\Column(name="textAttribute", type="string", length=255, nullable=false)
     */
    private $textattribute;

    /**
     * @var float|null
     *
     * @ORM\Column(name="score", type="float", precision=10, scale=0, nullable=true)
     */
    private $score;

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
