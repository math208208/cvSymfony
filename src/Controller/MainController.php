<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

final class MainController extends AbstractController
{
    // Route pour afficher un utilisateur, avec un slug
    #[Route('/{slug}', name: 'app_main')]
    public function show(string $slug, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        return $this->render('main/index.html.twig', [
            'user' => $user,
            'formations' => $user->getFormations(),
            'loisirs' => $user->getLoisirs(),

        ]);
    }
}