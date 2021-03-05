<?php

namespace App\Entity;

use App\Entity\Traits\Timerr;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
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
     * @Groups({"post_read", "com_read"})
     */
    private $id;


    /**
     * @ORM\Column(type="text")
     * @Groups({"post_read", "com_read"})
     * @Assert\NotBlank
     */
    private $message;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post_read", "com_read"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comment")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post_read", "com_read"})
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }
}
