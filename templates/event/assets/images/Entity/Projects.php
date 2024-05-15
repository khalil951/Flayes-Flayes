<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Projects
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity
 */
class Projects
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
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=900, nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="target_audience", type="string", length=255, nullable=true)
     */
    private $targetAudience;

    /**
     * @var string|null
     *
     * @ORM\Column(name="demand_in_market", type="string", length=255, nullable=true)
     */
    private $demandInMarket;

    /**
     * @var string|null
     *
     * @ORM\Column(name="development_timeline", type="string", length=255, nullable=true)
     */
    private $developmentTimeline;

    /**
     * @var float|null
     *
     * @ORM\Column(name="budget_funding_requirements", type="float", precision=10, scale=0, nullable=true)
     */
    private $budgetFundingRequirements;

    /**
     * @var string|null
     *
     * @ORM\Column(name="risk_analysis", type="string", length=1000, nullable=true)
     */
    private $riskAnalysis;

    /**
     * @var string|null
     *
     * @ORM\Column(name="market_strategy", type="string", length=1000, nullable=true)
     */
    private $marketStrategy;

    /**
     * @var string|null
     *
     * @ORM\Column(name="exit_strategy", type="string", length=1000, nullable=true)
     */
    private $exitStrategy;

    /**
     * @var string|null
     *
     * @ORM\Column(name="team_background", type="text", length=65535, nullable=true)
     */
    private $teamBackground;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tags", type="text", length=65535, nullable=true)
     */
    private $tags;

    /**
     * @var string|null
     *
     * @ORM\Column(name="unique_selling_points", type="string", length=1000, nullable=true)
     */
    private $uniqueSellingPoints;

    /**
     * @var string|null
     *
     * @ORM\Column(name="daily_price_of_assets", type="text", length=65535, nullable=true)
     */
    private $dailyPriceOfAssets;

    /**
     * @var string|null
     *
     * @ORM\Column(name="investors_equity", type="text", length=65535, nullable=true)
     */
    private $investorsEquity;

    /**
     * @var int|null
     *
     * @ORM\Column(name="category_id", type="integer", nullable=true)
     */
    private $categoryId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTargetAudience(): ?string
    {
        return $this->targetAudience;
    }

    public function setTargetAudience(?string $targetAudience): static
    {
        $this->targetAudience = $targetAudience;

        return $this;
    }

    public function getDemandInMarket(): ?string
    {
        return $this->demandInMarket;
    }

    public function setDemandInMarket(?string $demandInMarket): static
    {
        $this->demandInMarket = $demandInMarket;

        return $this;
    }

    public function getDevelopmentTimeline(): ?string
    {
        return $this->developmentTimeline;
    }

    public function setDevelopmentTimeline(?string $developmentTimeline): static
    {
        $this->developmentTimeline = $developmentTimeline;

        return $this;
    }

    public function getBudgetFundingRequirements(): ?float
    {
        return $this->budgetFundingRequirements;
    }

    public function setBudgetFundingRequirements(?float $budgetFundingRequirements): static
    {
        $this->budgetFundingRequirements = $budgetFundingRequirements;

        return $this;
    }

    public function getRiskAnalysis(): ?string
    {
        return $this->riskAnalysis;
    }

    public function setRiskAnalysis(?string $riskAnalysis): static
    {
        $this->riskAnalysis = $riskAnalysis;

        return $this;
    }

    public function getMarketStrategy(): ?string
    {
        return $this->marketStrategy;
    }

    public function setMarketStrategy(?string $marketStrategy): static
    {
        $this->marketStrategy = $marketStrategy;

        return $this;
    }

    public function getExitStrategy(): ?string
    {
        return $this->exitStrategy;
    }

    public function setExitStrategy(?string $exitStrategy): static
    {
        $this->exitStrategy = $exitStrategy;

        return $this;
    }

    public function getTeamBackground(): ?string
    {
        return $this->teamBackground;
    }

    public function setTeamBackground(?string $teamBackground): static
    {
        $this->teamBackground = $teamBackground;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function getUniqueSellingPoints(): ?string
    {
        return $this->uniqueSellingPoints;
    }

    public function setUniqueSellingPoints(?string $uniqueSellingPoints): static
    {
        $this->uniqueSellingPoints = $uniqueSellingPoints;

        return $this;
    }

    public function getDailyPriceOfAssets(): ?string
    {
        return $this->dailyPriceOfAssets;
    }

    public function setDailyPriceOfAssets(?string $dailyPriceOfAssets): static
    {
        $this->dailyPriceOfAssets = $dailyPriceOfAssets;

        return $this;
    }

    public function getInvestorsEquity(): ?string
    {
        return $this->investorsEquity;
    }

    public function setInvestorsEquity(?string $investorsEquity): static
    {
        $this->investorsEquity = $investorsEquity;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): static
    {
        $this->categoryId = $categoryId;

        return $this;
    }


}
