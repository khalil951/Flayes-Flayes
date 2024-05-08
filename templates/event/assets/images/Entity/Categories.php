<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity
 */
class Categories
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
     * @ORM\Column(name="category_name", type="string", length=255, nullable=false)
     */
    private $categoryName;

    /**
     * @var string
     *
     * @ORM\Column(name="subfield", type="string", length=255, nullable=false)
     */
    private $subfield;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type_of_funding", type="string", length=50, nullable=true)
     */
    private $typeOfFunding;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profitability_index", type="string", length=50, nullable=true)
     */
    private $profitabilityIndex;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): static
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    public function getSubfield(): ?string
    {
        return $this->subfield;
    }

    public function setSubfield(string $subfield): static
    {
        $this->subfield = $subfield;

        return $this;
    }

    public function getTypeOfFunding(): ?string
    {
        return $this->typeOfFunding;
    }

    public function setTypeOfFunding(?string $typeOfFunding): static
    {
        $this->typeOfFunding = $typeOfFunding;

        return $this;
    }

    public function getProfitabilityIndex(): ?string
    {
        return $this->profitabilityIndex;
    }

    public function setProfitabilityIndex(?string $profitabilityIndex): static
    {
        $this->profitabilityIndex = $profitabilityIndex;

        return $this;
    }


}
