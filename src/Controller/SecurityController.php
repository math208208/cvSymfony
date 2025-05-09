<?php

namespace App\Controller;

use App\Form\ConnectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request,): Response
    {
        $form = $this->createForm(ConnectType::class);

        $form->handleRequest($request);

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'login/index.html.twig',
            [
            'last_username' => $lastUsername,
            'error'         => $error,
            'connectForm' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank 
        - it will be intercepted by the logout key on your firewall.');
    }
}
