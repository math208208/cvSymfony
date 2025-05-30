<?php

namespace App\Entity;

use App\Entity\Trait\Images;
use App\EventListener\UserLinkedEntityListener;
use App\Repository\OutilRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;

#[Uploadable]
#[ORM\Entity(repositoryClass: OutilRepository::class)]
class Outil
{
    use Images;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'outils')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'boolean')]
    private bool $archived = false;

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;
        return $this;
    }


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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
}
