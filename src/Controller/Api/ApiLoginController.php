<?php

namespace App\Controller\Api;

use App\Entity\Admin;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class ApiLoginController extends AbstractController
{
    private $passwordHasher;
    private $jwtManager;
    private $entityManager;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager,
        EntityManagerInterface $entityManager
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/api/login', name: 'api_login', methods: "POST")]
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['message' => 'Case non remplis'], 400);
        }

        $user = $this->entityManager->getRepository(Admin::class)->findOneByEmail($data['email']);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['message' => 'Information non valide'], 401);
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
