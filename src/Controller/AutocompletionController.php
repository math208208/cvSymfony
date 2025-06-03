<?php

namespace App\Controller;

use App\Repository\UserPlateRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class AutocompletionController extends AbstractController
{

    #[Route('/autocomplete', name: 'autocomplete', methods: ['GET'])]
    public function index(Request $request, UserPlateRepository $repo, UserRepository $userRepo): JsonResponse
    {

        $q = $request->query->get('q', '');
        $results = $repo->findSuggestions($q);

        $suggestions = [];

        foreach ($results as $result) {
            $user = $userRepo->find($result['userId']);

            if ($user->isPrivate() === false) {
                $suggestions[] = [
                    'value' => $user->getNom() . ' ' . $user->getPrenom() . ' -> ' . $result["field"],
                    'slug' => $user->getSlug(),
                    'field' => $result["field"],
                ];
            }
        }



        return new JsonResponse($suggestions);
    }
}
