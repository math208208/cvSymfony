<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Loisir;
use App\Entity\User;
use App\Repository\FormationRepository;
use App\Repository\LoisirRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Symfony\Component\HttpFoundation\Request;

final class AccueilController extends AbstractController
{
    #[Route('/{slug}', name: 'app_accueil', requirements: ['slug' => '^(?!favicon\.ico$).+'])]
    public function show(
        string $slug,
        UserRepository $userRepository,
        FormationRepository $repoformations,
        LoisirRepository $repoloisirs,
        TranslationService $translator,
        Request $request,
        string $_locale
    ): Response {
        
        $this->denyAccessUnlessGranted('VIEW_PROFILE', $slug);

        $user = $userRepository->findOneBySlug($slug);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }


        $formations = $repoformations->findByUser($user);
        foreach ($formations as $key => $formation) {
            if ($formation->isArchived()) {
                unset($formations[$key]); 
            }
        }
        
        $loisirs = $repoloisirs->findByUser($user);
        foreach ($loisirs as $key => $loisir) {
            if ($loisir->isArchived()) {
                unset($loisirs[$key]); 
            }
        }

    
        $translatedProfession = $translator->translate(User::class, $user->getId(), 'profession', $user->getProfession() ?? 'Aucune donnée',$_locale);
        $translatedDescription = $translator->translate(User::class, $user->getId(), 'description', $user->getDescription() ?? 'Aucune donnée',$_locale);

        $translatedUser = [
            'user' => $user,
            'translated_profession' => $translatedProfession,
            'translated_description' => $translatedDescription
        ];

        

        $translatedformations = [];
    
        foreach ($formations as $formation) {
            $translatedIntitule = $translator->translate(Formation::class, $formation->getId(), 'intitule', $formation->getIntitule() ?? 'Aucune donnée',$_locale);
            $translatedLieu = $translator->translate(Formation::class, $formation->getId(), 'lieu', $formation->getLieu() ?? 'Aucune donnée',$_locale);

            $translatedformations[] = [
                'formation' => $formation,
                'translated_intitule' => $translatedIntitule,
                'translated_lieu' => $translatedLieu
            ];
        }

        
        $translatedLoisirs = [];
    
        foreach ($loisirs as $loisir) {
            $translatedName = $translator->translate(Loisir::class, $loisir->getId(), 'nom', $loisir->getNom() ?? 'Aucune donnée' ,$_locale);
            $translatedLoisirs[] = [
                'loisir' => $loisir,
                'translated_nom' => $translatedName
            ];
        }


        return $this->render('accueil/index.html.twig', [
            'user' => $translatedUser,
            
            'loisirs' => $translatedLoisirs,
            'formations' => $translatedformations,

        ]);
    }
}
