<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CvJsonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PopupController extends AbstractController
{
    #[Route('/cv/popup', name: 'cv_popup')]
    public function showPopup(
        Request $request,
        CvJsonService $cvService,
        string $_locale
    ): JsonResponse {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $email = $request->query->get('email');
        $cv = $cvService->getCvJson($email, $_locale);

        $html = $this->renderView('blog/popup.html.twig', [
            'cv' => $cv
        ]);

        return new JsonResponse(['html' => $html]);
    }
}


