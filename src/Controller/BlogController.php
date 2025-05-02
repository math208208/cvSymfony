<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

final class BlogController extends AbstractController
{
    #[Route('/{slug}/blog', name: 'app_blog')]
    public function show(string $slug, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('VIEW_PROFILE', $slug);

        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        return $this->render('blog/index.html.twig', [
            'user' => $user
        ]);
    }
}
