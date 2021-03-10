<?php

namespace App\Entity;

use App\Entity\Traits\Timerr;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=LikeRepository::class)
 * @ORM\Table(name="`like`")
 * @ORM\HasLifecycleCallbacks()
 */
class Like
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post_read")
     * @Assert\NotBlank
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("post_read")
     *
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="likes")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("post_read")
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
