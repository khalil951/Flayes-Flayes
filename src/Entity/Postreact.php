<?php

namespace App\Entity;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostReactRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PostReactRepository::class)]
#[ORM\Table(name: "post" )]

class Postreact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $reactId = null;

    #[ORM\Column(nullable: true)]
    private ?bool $islike = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $user = null;
  
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
