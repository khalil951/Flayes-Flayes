<?php

namespace App\Entity;

use App\Repository\PostReactRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $room_id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Author name is required")]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Content is required")]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(pattern:"/\.png$|\.jpg$/",message:"Image type should be png or jpg")]
    private ?string $img_url = null;

    #[ORM\Column(nullable: true)]
    private ?int $NumLikes = null;

    #[ORM\Column(nullable: true)]
    private ?int $NumDislikes = null;

    #[ORM\Column]
    private ?int $user_id = null;

    // #[ORM\Column(nullable: true)]
    // private ?bool $isLiked = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?Room $room = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostReact::class)]
    private Collection $postreacts;

    public function __construct()
    {
        $this->postreacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoomId(): ?int
    {
        return $this->room_id;
    }

    public function setRoomId(int $room_id): static
    {
        $this->room_id = $room_id;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->img_url;
    }

    public function setImgUrl(?string $img_url): static
    {
        $this->img_url = $img_url;

        return $this;
    }

    public function getNumLikes(): ?int
    {
        return $this->NumLikes;
    }

    public function setNumLikes(?int $NumLikes): static
    {
        $this->NumLikes = $NumLikes;

        return $this;
    }

    public function getNumDislikes(): ?int
    {
        return $this->NumDislikes;
    }

    public function setNumDislikes(?int $NumDislikes): static
    {
        $this->NumDislikes = $NumDislikes;

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

    // public function isIsLiked(): ?bool
    // {
    //     return $this->isLiked;
    // }

    // public function setIsLiked(?bool $isLiked): static
    // {
    //     $this->isLiked = $isLiked;

    //     return $this;
    // }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection<int, PostReact>
     */
    public function getPostreacts(): Collection
    {
        return $this->postreacts;
    }

    public function addPostreact(PostReact $postreact): static
    {
        if (!$this->postreacts->contains($postreact)) {
            $this->postreacts->add($postreact);
            $postreact->setPost($this);
        }

        return $this;
    }

    public function removePostreact(PostReact $postreact): static
    {
        if ($this->postreacts->removeElement($postreact)) {
            // set the owning side to null (unless already changed)
            if ($postreact->getPost() === $this) {
                $postreact->setPost(null);
            }
        }

        return $this;
    }


    public function isLiked(EntityManagerInterface $entityManager): bool
    {
        // Query the database to check if the post has any likes
        $postLikeCount = $entityManager->getRepository(PostReact::class)
            ->count(['post' => $this, 'Isliked' => true]);

        // Return true if there are likes, false otherwise
        return $postLikeCount > 0;
    }
   

}
