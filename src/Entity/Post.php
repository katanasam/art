<?php

namespace App\Entity;

use App\Entity\Traits\Timerr;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\Table(name="posts")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    // importe les propriéter de la classe timer
    /**
     * App\Entity\Traits\Timerr
     */
    use Timerr;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post_read")
     * @Assert\NotBlank
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post_read")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="post")
     * @Groups("post_read")
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @Groups("post_read")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="post")
     * @Groups("post_read")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     * @Groups("post_read")
     */
    private $comment;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comment = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPost($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPost() === $this) {
                $image->setPost(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }



}
