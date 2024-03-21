<?php

namespace App\Entity;

use App\Entity\Room;
use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: "post" )]


class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $postId = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img_url = null;

    #[ORM\Column(nullable: true)]
    private ?int $NumLikes = null;

    #[ORM\Column(nullable: true)]
    private ?int $NumDislikes = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?Room $room = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostReact::class)]
    private Collection $postreacts;

    public function __construct()
    {
        $this->postreacts = new ArrayCollection();
    }

    public function getPostId(): ?int
    {
        return $this->postId;
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

    public function setImgUrl(?string $imgUrl): static
    {
        $this->img_url = $imgUrl;

        return $this;
    }

    public function getNumlikes(): ?int
    {
        return $this->NumLikes;
    }

    public function setNumlikes(int $numlikes): static
    {
        $this->NumLikes = $numlikes;

        return $this;
    }

    public function getNumdislikes(): ?int
    {
        return $this->NumDislikes;
    }

    public function setNumdislikes(int $numdislikes): static
    {
        $this->NumDislikes = $numdislikes;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

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


}
