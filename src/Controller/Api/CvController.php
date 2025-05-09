<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CvController extends AbstractController
{
    #[Route('/api/cv', name: 'app_api_cv', methods: ['GET'])]
    public function getCv(Request $request, UserRepository $userRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $email = $request->query->get('email');

        if (!$email) {
            return $this->json(['error' => 'Email manquant'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        $cv = [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'profession' => $user->getProfession(),
            'email' => $user->getEmail(),
            'telephone' => $user->getTelephone(),
            'Formations' => array_map(
                function ($formation) {
                    return [
                    'Intitulé' => $formation->getIntitule(),
                    'Lieu' => $formation->getLieu(),
                    'Annee' => $formation->getAnnee(),
                    ];
                },
                $user->getFormations()->toArray()
            ),

            'Loisirs' => array_map(
                function ($loisirs) {
                    return [
                    'Nom' => $loisirs->getNom(),
                    ];
                },
                $user->getLoisirs()->toArray()
            ),

            'Experience Universitaire' => array_map(
                function ($experienceUni) {
                    return [
                    'Titre' => $experienceUni->getTitre(),
                    'Sous titre' => $experienceUni->getSousTitre(),
                    'Année' => $experienceUni->getAnnee(),

                    ];
                },
                $user->getExperiencesUni()->toArray()
            ),

            'Experience Professionnel' => array_map(
                function ($experiencePro) {
                    return [
                    'Poste' => $experiencePro->getPoste(),
                    'Entreprise' => $experiencePro->getEntreprise(),
                    'DateDebut' => $experiencePro->getdateDebut(),
                    'DateFin' => $experiencePro->getdateFin(),
                    ];
                },
                $user->getExperiencesPro()->toArray()
            ),
        ];

        return $this->json($cv);
    }
}
