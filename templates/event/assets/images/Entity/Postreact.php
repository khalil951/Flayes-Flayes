<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Postreact
 *
 * @ORM\Table(name="postreact", indexes={@ORM\Index(name="post_id", columns={"post_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Postreact
{
    /**
     * @var int
     *
     * @ORM\Column(name="react_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $reactId;

    /**
     * @var bool
     *
     * @ORM\Column(name="Islike", type="boolean", nullable=false)
     */
    private $islike;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_id", referencedColumnName="post_id")
     * })
     */
    private $post;

    public function getReactId(): ?int
    {
        return $this->reactId;
    }

    public function isIslike(): ?bool
    {
        return $this->islike;
    }

    public function setIslike(bool $islike): static
    {
        $this->islike = $islike;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }


}
