<?php
namespace App\Entity\Translation;

use App\Entity\ExperienceUni;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class ExperienceUniTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ExperienceUni::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExperienceUni $translatable= null;


    #[ORM\Column(length: 2)]
    private string $locale;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sousTitre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranslatable(): ?ExperienceUni
    {
        return $this->translatable;
    }

    public function setTranslatable(ExperienceUni $translatable): static
    {
        $this->translatable = $translatable;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

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


    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSousTitre(): ?string
    {
        return $this->sousTitre;
    }

    public function setSousTitre(?string $sousTitre): static
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }
}
