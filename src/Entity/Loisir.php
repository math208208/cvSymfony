<?php

namespace App\Entity;

use App\Entity\Trait\Images;
use App\Entity\Translation\LoisirTranslation;
use App\Repository\LoisirRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;

#[Uploadable]
#[ORM\Entity(repositoryClass: LoisirRepository::class)]
class Loisir
{
    use Images;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'loisirs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    
    #[ORM\OneToMany(mappedBy: 'translatable', targetEntity: LoisirTranslation::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $translations;

    public function __toString(): string
    {
        return $this->nom ?? 'Loisir';
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

}
