<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Room
 *
 * @ORM\Table(name="room")
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    /**
     * @var int
     *
     * @ORM\Column(name="room_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $roomId = null;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=100, nullable=false)
     */
    #[ORM\Column(length: 255)]
    private ?string $category = null;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_category", type="string", length=100, nullable=false)
     */
    #[ORM\Column(length: 255)]
    private ?string $sub_category = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: Post::class)]
    private Collection $posts;

    public function getRoomId(): ?int
    {
        return $this->roomId;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getSubCategory(): ?string
    {
        return $this->sub_category;
    }

    public function setSubCategory(string $subCategory): static
    {
        $this->sub_category = $subCategory;

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
