<?php

namespace App\Entity;

use App\Entity\Trait\Images;
use App\Entity\Translation\FormationTranslation;
use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;

#[Uploadable]
#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{

    use Images;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;


    #[ORM\OneToMany(mappedBy: 'translatable', targetEntity: FormationTranslation::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $translations;

    public function __toString(): string
    {
        return $this->user." -> ".$this->intitule ?? 'Formation';
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
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function SetPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }


}
