<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

final class CompetencesController extends AbstractController
{
    #[Route('/{slug}/competences', name: 'app_competences')]
    public function show(string $slug, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        return $this->render('competences/index.html.twig', [
            'user' => $user,
            'langues' => $user->getLangues(),
            'competences' => $user->getCompetences(),
            'outils' => $user->getOutils(),
        ]);
    }
}
