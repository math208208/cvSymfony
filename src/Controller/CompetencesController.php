<?php

namespace App\Controller;

use App\Entity\Langage;
use App\Entity\User;
use App\Repository\CompetenceRepository;
use App\Repository\LangageRepository;
use App\Repository\OutilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

final class CompetencesController extends AbstractController
{
    #[Route('/{slug}/competences', name: 'app_competences')]
    public function show(
        string $slug,
        UserRepository $userRepository,
        CompetenceRepository $repoCompetences,
        OutilRepository $repoOutils,
        LangageRepository $repolangages,
        TranslationService $translator,
        Security $security,
        Request $request,
        string $_locale
    ): Response {
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


        $translatedProfession = $translator->translate(
            User::class,
            $user->getId(),
            'profession',
            $user->getProfession() ?? 'Aucune donnée',
            $_locale
        );
        $translatedDescription = $translator->translate(
            User::class,
            $user->getId(),
            'description',
            $user->getDescription() ?? 'Aucune donnée',
            $_locale
        );

        $translatedUser = [
            'user' => $user,
            'translated_profession' => $translatedProfession,
            'translated_description' => $translatedDescription
        ];


        $translatedLangages = [];

        foreach ($langages as $langage) {
            $translatedNameLangage = $translator->translate(
                Langage::class,
                $langage->getId(),
                'nomLangue',
                $langage->getNomLangue() ?? 'Aucune donnée',
                $_locale
            );
            $translatedLangages[] = [
                'langue' => $langage,
                'translated_nom' => $translatedNameLangage
            ];
        }

        $admin = $security->getUser();
        /** @var \App\Entity\Admin $admin */
        $email = $admin->getEmail();
        $userCo = $userRepository->findOneBy(['email' => $email]);
        if ($this->isGranted('ROLE_PRO')) {
            $layout = 'base/pro/index.html.twig';
        } else if ($this->isGranted('ROLE_USER') || $this->isGranted('ROLE_ADMIN')) {
            if($slug===$userCo->getSlug()){
                $layout = 'base/user/index.html.twig';
            }else{
                $layout = 'base/user/explo.html.twig';
            }
            
        } 

        return $this->render(
            'competences/index.html.twig',
            [
                'layout' => $layout,
                'user' => $translatedUser,
                'langues' => $translatedLangages,
                'competences' => $competences,
                'outils' => $outils,
                'userCo'=>$userCo,

            ]
        );
    }
}
