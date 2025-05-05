<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;

final class BlogController extends AbstractController
{
    #[Route('/{slug}/blog', name: 'app_blog')]
    public function show(
        string $slug,
        UserRepository $userRepository,
        TranslationService $translator,
        string $_locale
    ): Response {
        $this->denyAccessUnlessGranted('VIEW_PROFILE', $slug);

        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }


        $translatedProfession = $translator->translate(User::class, $user->getId(), 'profession', $user->getProfession() ?? 'Aucune donnée', $_locale);
        $translatedDescription = $translator->translate(User::class, $user->getId(), 'description', $user->getDescription() ?? 'Aucune donnée', $_locale);

        $translatedUser = [
            'user' => $user,
            'translated_profession' => $translatedProfession,
            'translated_description' => $translatedDescription
        ];

        return $this->render('blog/index.html.twig', [
            'user' => $translatedUser
        ]);
    }
}
