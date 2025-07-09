<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $username = null;

    #[ORM\Column(length: 80)]
    private ?string $password = null;

    #[ORM\Column(length: 160)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    /**
     * @var Collection<int, Trip>
     */
    #[ORM\OneToMany(targetEntity: Trip::class, mappedBy: 'user')]
    private Collection $trip;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user')]
    private Collection $comment;

    public function __construct()
    {
        $this->trip = new ArrayCollection();
        $this->comment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrip(): Collection
    {
        return $this->trip;
    }

    public function addTrip(Trip $trip): static
    {
        if (!$this->trip->contains($trip)) {
            $this->trip->add($trip);
            $trip->setUser($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): static
    {
        if ($this->trip->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getUser() === $this) {
                $trip->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}
