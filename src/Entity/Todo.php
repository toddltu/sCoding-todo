<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TodoRepository")
 */
class Todo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Type(
     *     type="string"
     * )
     */
    private $content;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @Assert\Type(
     *     type="integer"
     * )
     * @ORM\Column(type="integer", nullable=true)
     */
    private $inStatus;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /*public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }*/

    public function getInStatus(): ?int
    {
        return $this->inStatus;
    }

    public function setInStatus(?int $inStatus): self
    {
        $this->inStatus = $inStatus;

        return $this;
    }
}
