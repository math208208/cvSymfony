<?php

namespace App\Controller\Api;

use App\Entity\ExperiencePro;
use App\Entity\ExperienceUni;
use App\Entity\Formation;
use App\Entity\Langage;
use App\Entity\Loisir;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\CvJsonService;
use App\Service\TranslationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class CvController extends AbstractController
{
    #[Route('/api/cv', name: 'app_api_cv', methods: ['GET'])]
    public function getCv(
        Request $request,
        UserRepository $userRepository,
        TranslationService $translator,
        CvJsonService $cvService,
        string $_locale,
    ): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $email = $request->query->get('email');

        if (!$email) {
            return $this->json(['error' => 'Email manquant'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        $cv = $cvService->getCvJson($email, $_locale);

        return $this->json($cv);
    }
}
