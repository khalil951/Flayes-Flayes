<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="room_id", columns={"room_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="post_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $postId;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=20, nullable=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=200, nullable=false)
     */
    private $content;

    /**
     * @var string|null
     *
     * @ORM\Column(name="img_url", type="string", length=100, nullable=true)
     */
    private $imgUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="NumLikes", type="integer", nullable=false)
     */
    private $numlikes;

    /**
     * @var int
     *
     * @ORM\Column(name="NumDislikes", type="integer", nullable=false)
     */
    private $numdislikes;

    /**
     * @var \Room
     *
     * @ORM\ManyToOne(targetEntity="Room")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="room_id", referencedColumnName="room_id")
     * })
     */
    private $room;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

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
        return $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): static
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function getNumlikes(): ?int
    {
        return $this->numlikes;
    }

    public function setNumlikes(int $numlikes): static
    {
        $this->numlikes = $numlikes;

        return $this;
    }

    public function getNumdislikes(): ?int
    {
        return $this->numdislikes;
    }

    public function setNumdislikes(int $numdislikes): static
    {
        $this->numdislikes = $numdislikes;

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


}
