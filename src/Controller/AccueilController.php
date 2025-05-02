<?php

namespace App\Controller;


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



        return $this->render('accueil/index.html.twig', [
            'user' => $user,
            'formations' => $formations,
            'loisirs' => $loisirs,
        ]);
    }
}
