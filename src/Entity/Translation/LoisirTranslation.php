<?php
namespace App\Entity\Translation;

use App\Entity\Loisir;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class LoisirTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Loisir::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Loisir $translatable= null;

    #[ORM\Column(length: 2)]
    private string $locale;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTranslatable(): ?Loisir
    {
        return $this->translatable;
    }

    public function setTranslatable(Loisir $translatable): static
    {
        $this->translatable = $translatable;

        return $this;
    }
}
