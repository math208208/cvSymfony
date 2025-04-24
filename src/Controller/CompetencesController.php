<?php

namespace App\Controller;

use App\Entity\Translation\LangageTranslation;
use App\Repository\LangageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Symfony\Component\HttpFoundation\Request;

final class CompetencesController extends AbstractController
{
    #[Route('/{slug}/competences', name: 'app_competences')]
    public function show(string $slug, UserRepository $userRepository, LangageRepository $repolangages,TranslationService $translator,Request $request): Response
    {
        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        $langages = $repolangages->findByUser($user);  
        $locale = $request->getLocale();
        foreach ($langages as $langage) {
            if ($translation = $translator->translate($langage, $locale, LangageTranslation::class)) {
                $langage->setNomLangue($translation->getNomLangue());
            }
        }
        return $this->render('competences/index.html.twig', [
            'user' => $user,
            'langues' => $user->getLangues(),
            'competences' => $user->getCompetences(),
            'outils' => $user->getOutils(),
        ]);
    }
}
