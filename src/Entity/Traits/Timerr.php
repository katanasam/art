<?php

namespace App\Entity\Traits;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait Timer
 * @package App\Entity\Traits
 */
Trait Timerr
{

    /**
     * @ORM\Column(type="datetime")
     *@Groups("post:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("post:read")
     */
    private $updateAt;


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