<?php

namespace App\Controller;

use App\Entity\Translation\FormationTranslation;
use App\Entity\Translation\LoisirTranslation;
use App\Entity\Translation\UserTranslation;
use App\Repository\FormationRepository;
use App\Repository\LoisirRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Symfony\Component\HttpFoundation\Request;
final class MainController extends AbstractController
{
    #[Route('/{slug}', name: 'app_main')]
    public function show(string $slug, UserRepository $userRepository, FormationRepository $repoformations,
    LoisirRepository $repoloisirs,TranslationService $translator, Request $request

    ): Response
    {
        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        $formations = $repoformations->findByUser($user);  
        $loisirs = $repoloisirs->findByUser($user);  
        $locale = $request->getLocale();


        foreach ($formations as $formation) {
            if ($translation = $translator->translate($formation, $locale, FormationTranslation::class)) {
                $formation->setIntitule($translation->getIntitule());
            }
        }

        foreach ($loisirs as $loisir) {
            if ($translation = $translator->translate($loisir, $locale, LoisirTranslation::class)) {
                $loisir->setNom($translation->getNom());
            }
        }


        if ($translation = $translator->translate($user, $locale, UserTranslation::class)) {
            $user->setProfession($translation->getProfession());
            $user->setDescription($translation->getDescription());
        }
        


        return $this->render('main/index.html.twig', [
            'user' => $user,
            'formations' => $formations,
            'loisirs' => $loisirs,

        ]);
    }

}