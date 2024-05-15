<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $catgory = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Name is required")]
    #[Assert\Regex(pattern:"/^[a-zA-Z\s]+$/",message:"Name must contain only letters")]
    private ?string $sub_category = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Description is required")]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: Post::class, cascade: ['remove'])]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatgory(): ?string
    {
        return $this->catgory;
    }

    public function setCatgory(string $catgory): static
    {
        $this->catgory = $catgory;

        return $this;
    }

    public function getSubCategory(): ?string
    {
        return $this->sub_category;
    }

    public function setSubCategory(string $sub_category): static
    {
        $this->sub_category = $sub_category;

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

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setRoom($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getRoom() === $this) {
                $post->setRoom(null);
            }
        }

        return $this;
    }
}
