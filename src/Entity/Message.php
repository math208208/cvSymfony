<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $message;

    #[ORM\ManyToOne(targetEntity: Professionnel::class)]
    private ?Professionnel $expediteur = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $receveur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getExpediteur(): ?Professionnel
    {
        return $this->expediteur;
    }

    public function setExpediteur(?Professionnel $expediteur): self
    {
        $this->expediteur = $expediteur;
        return $this;
    }

    public function getReceveur(): ?User
    {
        return $this->receveur;
    }

    public function setReceveur(?User $receveur): self
    {
        $this->receveur = $receveur;
        return $this;
    }
}