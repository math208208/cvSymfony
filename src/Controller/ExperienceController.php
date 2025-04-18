<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

final class ExperienceController extends AbstractController
{
    #[Route('/{slug}/experiences', name: 'app_experience')]
    public function show(string $slug, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        return $this->render('experiences/index.html.twig', [
            'user' => $user,
            'experiencesUni' => $user->getExperiencesUni(),
            'experiencesPro' => $user->getExperiencesPro(),

        ]);
    }
}