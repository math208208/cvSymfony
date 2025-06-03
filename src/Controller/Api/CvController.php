<?php

namespace App\Controller\Api;

use App\Entity\ExperiencePro;
use App\Entity\ExperienceUni;
use App\Entity\Formation;
use App\Entity\Langage;
use App\Entity\Loisir;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class CvController extends AbstractController
{
    #[Route('/api/cv', name: 'app_api_cv', methods: ['GET'])]
    public function getCv(
        Request $request,
        UserRepository $userRepository,
        TranslationService $translator,
        string $_locale,
    ): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $email = $request->query->get('email');

        if (!$email) {
            return $this->json(['error' => 'Email manquant'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        $cv =  [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'profession' => $translator->translate(
                User::class,
                $user->getId(),
                'profession',
                $user->getProfession(),
                $_locale
            ),

            'description'  => $translator->translate(
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
                'nom' => $translator->translate(
                    Langage::class,
                    $langue->getId(),
                    'nomLangue',
                    $langue->getNomLangue(),
                    $_locale
                ),
                'niveau' => $translator->translate(
                    Langage::class,
                    $langue->getId(),
                    'niveau',
                    $langue->getNiveau(),
                    $_locale
                ),
            ], $user->getLangues()->toArray()),

            'loisirs' => array_map(fn($loisir) => [
                'nom' => $translator->translate(
                    Loisir::class,
                    $loisir->getId(),
                    'nom',
                    $loisir->getNom(),
                    $_locale
                ),
            ], $user->getLoisirs()->toArray()),



            'experiencesPro' => array_map(fn($exp) => [
                'poste' => $translator->translate(
                    ExperiencePro::class,
                    $exp->getId(),
                    'poste',
                    $exp->getPoste(),
                    $_locale
                ),
                'entreprise' => $exp->getEntreprise(),
                'dateDebut' => $exp->getDateDebut(),
                'dateFin' => $exp->getDateFin() ?? null,
            ], $user->getExperiencesPro()->toArray()),

            'experiencesUni' => array_map(fn($exp) => [
                'titre' => $translator->translate(
                    ExperienceUni::class,
                    $exp->getId(),
                    'titre',
                    $exp->getTitre(),
                    $_locale
                ),
                'sousTitre' => $translator->translate(
                    ExperienceUni::class,
                    $exp->getId(),
                    'sousTitre',
                    $exp->getSousTitre(),
                    $_locale
                ),
                'annee' => $exp->getAnnee(),
            ], $user->getExperiencesUni()->toArray()),

            'formations' => array_map(fn($formation) => [
                'intitule' => $translator->translate(
                    Formation::class,
                    $formation->getId(),
                    'intitule',
                    $formation->getintitule(),
                    $_locale
                ),
                'lieu' => $formation->getLieu(),
                'annee' => $formation->getAnnee(),
            ], $user->getFormations()->toArray()),
        ];

        return $this->json($cv);
    }
}
