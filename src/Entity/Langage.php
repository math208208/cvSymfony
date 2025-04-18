<?php

namespace App\Entity;

use App\Repository\LangageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LangageRepository::class)]
class Langage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomLangue = null;

    #[ORM\Column(length: 255)]
    private ?string $niveau = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'langage')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLangue(): ?string
    {
        return $this->nomLangue;
    }

    public function setNomLangue(string $nomLangue): static
    {
        $this->nomLangue = $nomLangue;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }
}
