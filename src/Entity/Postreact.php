<?php

namespace App\Entity;

use App\Repository\PostReactRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostReactRepository::class)]
class PostReact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $post_id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Isliked = null;

    #[ORM\ManyToOne(inversedBy: 'postreacts')]
    private ?Post $post = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostId(): ?int
    {
        return $this->post_id;
    }

    public function setPostId(int $post_id): static
    {
        $this->post_id = $post_id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function isIsliked(): ?bool
    {
        return $this->Isliked;
    }

    public function setIsliked(?bool $Isliked): static
    {
        $this->Isliked = $Isliked;

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
