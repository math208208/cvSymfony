<?php
namespace App\Entity;

use App\Entity\ExperiencePro;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class ExperienceProTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ExperiencePro::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExperiencePro $translatable= null;


    #[ORM\Column(length: 2)]
    private string $locale;

    #[ORM\Column(length: 255)]
    private string $poste;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranslatable(): ?ExperiencePro
    {
        return $this->translatable;
    }

    public function setTranslatable(ExperiencePro $translatable): static
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

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;

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
}
