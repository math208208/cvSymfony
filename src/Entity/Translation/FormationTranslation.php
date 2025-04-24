<?php
namespace App\Entity\Translation;

use App\Entity\Formation;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class FormationTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $translatable= null;
    
    #[ORM\Column(length: 2)]
    private string $locale;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
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

    public function getTranslatable(): ?Formation
    {
        return $this->translatable;
    }

    public function setTranslatable(Formation $translatable): static
    {
        $this->translatable = $translatable;

        return $this;
    }


}
