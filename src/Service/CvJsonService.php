<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Elastic\Elasticsearch\ClientBuilder;

class CvJsonService
{
    public function __construct(
        private UserRepository $userRepository,
        private TranslationService $translator, 
    ) {}

    public function getCvJson(string $email, string $_locale): array
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new \Exception('Utilisateur non trouvé');
        }

        return [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'profession' => $this->translator->translate(
                User::class,
                $user->getId(),
                'profession',
                $user->getProfession(),
                $_locale
            ),
            'description' => $this->translator->translate(
                User::class,
                $user->getId(),
                'description',
                $user->getDescription(),
                $_locale
            ),
            'email' => $user->getEmail(),
            'telephone' => $user->getTelephone(),
            'image' => $user->getImageName(),

            'outils' => array_map(fn($outil) => $outil->getNom(), $user->getOutils()->toArray()),

            'competences' => array_map(fn($competence) => $competence->getNom(), $user->getCompetences()->toArray()),

            'langues' => array_map(fn($langue) => [
                'nom' => $this->translator->translate(
                    \App\Entity\Langage::class,
                    $langue->getId(),
                    'nomLangue',
                    $langue->getNomLangue(),
                    $_locale
                ),
                'niveau' => $this->translator->translate(
                    \App\Entity\Langage::class,
                    $langue->getId(),
                    'niveau',
                    $langue->getNiveau(),
                    $_locale
                ),
            ], $user->getLangues()->toArray()),

            'loisirs' => array_map(fn($loisir) => [
                'nom' => $this->translator->translate(
                    \App\Entity\Loisir::class,
                    $loisir->getId(),
                    'nom',
                    $loisir->getNom(),
                    $_locale
                ),
            ], $user->getLoisirs()->toArray()),

            'experiencesPro' => array_map(fn($exp) => [
                'poste' => $this->translator->translate(
                    \App\Entity\ExperiencePro::class,
                    $exp->getId(),
                    'poste',
                    $exp->getPoste(),
                    $_locale
                ),
                'entreprise' => $exp->getEntreprise(),
                'dateDebut' => $exp->getDateDebut(),
                'dateFin' => $exp->getDateFin(),
            ], $user->getExperiencesPro()->toArray()),

            'experiencesUni' => array_map(fn($exp) => [
                'titre' => $this->translator->translate(
                    \App\Entity\ExperienceUni::class,
                    $exp->getId(),
                    'titre',
                    $exp->getTitre(),
                    $_locale
                ),
                'sousTitre' => $this->translator->translate(
                    \App\Entity\ExperienceUni::class,
                    $exp->getId(),
                    'sousTitre',
                    $exp->getSousTitre(),
                    $_locale
                ),
                'annee' => $exp->getAnnee(),
            ], $user->getExperiencesUni()->toArray()),

            'formations' => array_map(fn($formation) => [
                'intitule' => $this->translator->translate(
                    \App\Entity\Formation::class,
                    $formation->getId(),
                    'intitule',
                    $formation->getIntitule(),
                    $_locale
                ),
                'lieu' => $formation->getLieu(),
                'annee' => $formation->getAnnee(),
            ], $user->getFormations()->toArray()),
        ];
    }
}