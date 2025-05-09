<?php

namespace App\Entity\Translation;

use App\Repository\TranslationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class Translation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id = null;

    #[ORM\Column(type: 'string')]
    private string $entity;

    #[ORM\Column(type: 'integer')]
    private int $entityId;

    #[ORM\Column(type: 'string')]
    private string $attribute;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $personne = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $fr = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $en = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $es = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): self
    {
        $this->entityId = $entityId;
        return $this;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(string $attribute): self
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function getPersonne(): ?string
    {
        return $this->personne;
    }

    public function setPersonne(?string $personne): self
    {
        $this->personne = $personne;
        return $this;
    }

    public function getFr(): ?string
    {
        return $this->fr;
    }

    public function setFr(?string $fr): self
    {
        $this->fr = $fr;
        return $this;
    }

    public function getEn(): ?string
    {
        return $this->en;
    }

    public function setEn(?string $en): self
    {
        $this->en = $en;
        return $this;
    }

    public function getEs(): ?string
    {
        return $this->es;
    }

    public function setEs(?string $es): self
    {
        $this->es = $es;
        return $this;
    }
}
