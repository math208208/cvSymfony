<?php

namespace App\Controller;

use App\Entity\Translation\LangageTranslation;
use App\Repository\CompetenceRepository;
use App\Repository\LangageRepository;
use App\Repository\OutilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Symfony\Component\HttpFoundation\Request;

final class CompetencesController extends AbstractController
{
    #[Route('/{slug}/competences', name: 'app_competences')]
    public function show(string $slug, UserRepository $userRepository, CompetenceRepository $repoCompetences,OutilRepository $repoOutils,LangageRepository $repolangages,TranslationService $translator,Request $request): Response
    {
        $this->denyAccessUnlessGranted('VIEW_PROFILE', $slug);

        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        $langages = $repolangages->findByUser($user);
        foreach ($langages as $key => $langage) {
            if ($langage->isArchived()) {
                unset($langages[$key]); 
            }
        }

        $outils = $repoOutils->findByUser($user);
        foreach ($outils as $key => $outil) {
            if ($outil->isArchived()) {
                unset($outils[$key]); 
            }
        }
        
        $competences = $repoCompetences->findByUser($user);
        foreach ($competences as $key => $competence) {
            if ($competence->isArchived()) {
                unset($competences[$key]); 
            }
        }



        
        return $this->render('competences/index.html.twig', [
            'user' => $user,
            'langues' => $langages,
            'competences' => $competences,
            'outils' => $outils,
        ]);
    }
}
