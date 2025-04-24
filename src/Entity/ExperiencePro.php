<?php

namespace App\Entity;

use App\Entity\ExperienceProTranslation as EntityExperienceProTranslation;
use App\Entity\Translation\ExperienceProTranslation;
use App\Repository\ExperienceProRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienceProRepository::class)]
class ExperiencePro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $poste = null;


    #[ORM\Column(length: 255)]
    private ?string $entreprise = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $dateDebut = null;

    #[ORM\Column(nullable: true)]
    private ?int $dateFin = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'experiencesPro')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'translatable', targetEntity: ExperienceProTranslation::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $translations;


    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getTranslations(): ?Collection
    {
        return $this->translations;
    }

    public function setTranslations(Collection $translations): static
    {
        $this->translations = $translations;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebut(): ?int
    {
        return $this->dateDebut;
    }

    public function setDateDebut(int $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?int
    {
        return $this->dateFin;
    }

    public function setDateFin(?int $dateFin): static
    {
        $this->dateFin = $dateFin;

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

    
    public function __toString(): string
    {
        return $this->user." -> ".$this->poste ?? 'Expérience';
    }
}
