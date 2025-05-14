<?php

namespace App\Controller;

use App\Entity\ExperiencePro;
use App\Entity\ExperienceUni;
use App\Entity\User;
use App\Repository\ExperienceProRepository;
use App\Repository\ExperienceUniRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;

final class BlogController extends AbstractController
{
    #[Route('/{_locale}', name: 'app_blog')]
    public function show(
        UserRepository $userRepository,
        ExperienceProRepository $repoExpPro,
        ExperienceUniRepository $repoExpUni,
        TranslationService $translator,
        string $_locale
    ): Response {

        $users = $userRepository->findAll();
        $translatedUsers = [];

        foreach ($users as $user) {
            if (!$user->isPrivate()) {
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

                $translatedUsers[] = [
                    'user' => $user,
                    'translated_profession' => $translatedProfession,
                    'translated_description' => $translatedDescription,
                ];
            }
        }

        $layout = $this->isGranted('ROLE_PRO')
            ? 'base/pro/index.html.twig'
            : 'base/accueil/index.html.twig';

        return $this->render('blog/index.html.twig', [
            'translatedUsers' => $translatedUsers,
            'layout' => $layout,
        ]);
    }
}
