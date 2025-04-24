<?php
namespace App\Entity\Translation;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Stmt\UseUse;

#[ORM\Entity]
class UserTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $translatable= null;


    #[ORM\Column(length: 2)]
    private string $locale;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;


    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $description = null;

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;

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

    public function getTranslatable(): ?User
    {
        return $this->translatable;
    }

    public function setTranslatable(User $translatable): static
    {
        $this->translatable = $translatable;

        return $this;
    }
}
