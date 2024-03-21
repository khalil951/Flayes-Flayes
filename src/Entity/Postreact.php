<?php

namespace App\Entity;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostReactRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * Postreact
 *
 * @ORM\Table(name="postreact", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="post_id", columns={"post_id"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: PostReactRepository::class)]

class Postreact
{
    /**
     * @var int
     *
     * @ORM\Column(name="react_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $reactId = null;

    /**
     * @var bool
     *
     * @ORM\Column(name="Islike", type="boolean", nullable=false)
     */
    #[ORM\Column(nullable: true)]
    private ?bool $islike = null;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $user = null;
    /**
     * @var \Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_id", referencedColumnName="post_id")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'postreacts')]
    private ?Post $post = null;

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
