<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Results
 *
 * @ORM\Table(name="results", indexes={@ORM\Index(name="Idevent", columns={"Idevent"})})
 * @ORM\Entity
 */
class Results
{
    /**
     * @var int
     *
     * @ORM\Column(name="Idresults", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idresults;

    /**
     * @var int
     *
     * @ORM\Column(name="scoreone", type="integer", nullable=false)
     */
    private $scoreone;

    /**
     * @var int
     *
     * @ORM\Column(name="scoretwo", type="integer", nullable=false)
     */
    private $scoretwo;

    /**
     * @var \Event
     *
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Idevent", referencedColumnName="Idevent")
     * })
     */
    private $idevent;

    public function getIdresults(): ?int
    {
        return $this->idresults;
    }

    public function getScoreone(): ?int
    {
        return $this->scoreone;
    }

    public function setScoreone(int $scoreone): static
    {
        $this->scoreone = $scoreone;

        return $this;
    }

    public function getScoretwo(): ?int
    {
        return $this->scoretwo;
    }

    public function setScoretwo(int $scoretwo): static
    {
        $this->scoretwo = $scoretwo;

        return $this;
    }

    public function getIdevent(): ?Event
    {
        return $this->idevent;
    }

    public function setIdevent(?Event $idevent): static
    {
        $this->idevent = $idevent;

        return $this;
    }


}
