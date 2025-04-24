<?php

namespace App\Entity;

use App\Entity\Trait\Images;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;

#[Uploadable] 
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('email')]
class User
{
    use Images;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;


    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true , length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $linkdin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $github = null;



    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ExperiencePro::class)]
    private Collection $experiencesPro;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ExperienceUni::class)]
    private Collection $experiencesUni;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Formation::class)]
    private Collection $formations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Loisir::class)]
    private Collection $loisirs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Langage::class)]
    private Collection $langues;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Competence::class)]
    private Collection $competences;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Outil::class)]
    private Collection $outils;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $slug = null;


    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    #[ORM\PrePersist]  
    public function generateSlug(): void
    {
        // Générer le slug à partir du nom et prénom si ce n'est pas déjà fait
        if (empty($this->slug)) {
            $slugTemp = $this->prenom . '-' . $this->nom;

            $slugTemp = strtolower($slugTemp);

            $this->slug = str_replace(
                ['á', 'à', 'â', 'ä', 'ã', 'å', 'ç', 'é', 'è', 'ê', 'ë', 'í', 'ï', 'î', 'ì', 'ñ', 'ó', 'ò', 'ô', 'ö', 'õ', 'ú', 'ù', 'û', 'ü', 'ý'],
                ['a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y'],
                $slugTemp
            );
        }
    }


    public function __construct()
    {
        $this->experiencesPro = new ArrayCollection();
        $this->experiencesUni = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->loisirs = new ArrayCollection();
        $this->langues = new ArrayCollection();
        $this->competences = new ArrayCollection();
        $this->outils = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $Nom): static
    {
        $this->nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->prenom = $Prenom;

        return $this;
    }

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


   



    public function getExperiencesUni(): Collection
    {
        return $this->experiencesUni;
    }

    public function getFormations(): Collection
    {
        return $this->formations;
    }


    public function getExperiencesPro(): Collection
    {
        return $this->experiencesPro;
    }


    public function getLoisirs(): Collection
    {
        return $this->loisirs;
    }

    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function getLangues(): Collection
    {
        return $this->langues;
    }

    public function getOutils(): Collection
    {
        return $this->outils;
    }



    public function __toString(): string
    {
        return $this->getPrenom() . " " . $this->getNom();
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getLinkdin(): ?string
    {
        return $this->linkdin;
    }

    public function setLinkdin(?string $linkdin): static
    {
        $this->linkdin = $linkdin;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): static
    {
        $this->github = $github;

        return $this;
    }
}
