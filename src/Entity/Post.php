<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\Table(name="posts")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read"))
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *@Groups("post:read")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     *@Groups("post:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *@Groups("post:read")
     */
    private $updateAt;



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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * la methode sera appeler avant la creation dun post
     * @ORM\PrePersist()
     * la methode sera appeler aprés la Modification dun post
     * @ORM\PreUpdate())
     */
    public function UpdateDate(){

        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdateAt(new \DateTimeImmutable());
    }


}
