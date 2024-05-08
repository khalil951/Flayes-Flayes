<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bet
 *
 * @ORM\Table(name="bet", indexes={@ORM\Index(name="Idevent", columns={"Idevent"}), @ORM\Index(name="Iduser", columns={"Iduser"})})
 * @ORM\Entity
 */
class Bet
{
    /**
     * @var int
     *
     * @ORM\Column(name="Idbet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idbet;

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
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=false)
     */
    private $state;

    /**
     * @var \Event
     *
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Idevent", referencedColumnName="Idevent")
     * })
     */
    private $idevent;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Iduser", referencedColumnName="id")
     * })
     */
    private $iduser;

    public function getIdbet(): ?int
    {
        return $this->idbet;
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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

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

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }


}
