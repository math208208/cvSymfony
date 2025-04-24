<?php
namespace App\Entity\Translation;

use App\Entity\Langage;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class LangageTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Langage::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Langage $translatable= null;


    #[ORM\Column(length: 2)]
    private string $locale;

    #[ORM\Column(length: 255)]
    private ?string $nomLangue = null;


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

    public function getNomLangue(): ?string
    {
        return $this->nomLangue;
    }

    public function setNomLangue(string $nomLangue): static
    {
        $this->nomLangue = $nomLangue;

        return $this;
    }

    public function getTranslatable(): ?Langage
    {
        return $this->translatable;
    }

    public function setTranslatable(Langage $translatable): static
    {
        $this->translatable = $translatable;

        return $this;
    }
}
