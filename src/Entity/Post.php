<?php

namespace App\Entity;

use App\Entity\Room;
use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="room_id", columns={"room_id"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: PostRepository::class)]

class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="post_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $postId = null;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=20, nullable=false)
     */
    #[ORM\Column(length: 255)]
    private ?string $author = null;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=200, nullable=false)
     */
    #[ORM\Column(length: 255)]
    private ?string $content = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="img_url", type="string", length=100, nullable=true)
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img_url = null;

    /**
     * @var int
     *
     * @ORM\Column(name="NumLikes", type="integer", nullable=false)
     */
    #[ORM\Column(nullable: true)]
    private ?int $NumLikes = null;

    /**
     * @var int
     *
     * @ORM\Column(name="NumDislikes", type="integer", nullable=false)
     */
    #[ORM\Column(nullable: true)]
    private ?int $NumDislikes = null;

    /**
     * @var \Room
     *
     * @ORM\ManyToOne(targetEntity="Room")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="room_id", referencedColumnName="room_id")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?Room $room = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostReact::class)]
    private Collection $postreacts;

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
