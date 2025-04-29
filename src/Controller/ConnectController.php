<?php

namespace App\Controller;

use App\Form\ConnectType;
use App\Entity\Admin;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Annotation\Route;

class ConnectController extends AbstractController
{
    #[Route('/connect', name: 'app_connect')]
    public function login(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage
    ): Response {
        $form = $this->createForm(ConnectType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $plainPassword = $data['plainPassword'];

            $admin = $em->getRepository(Admin::class)->findOneBy(['email' => $email]);

            if (!$admin) {
                $this->addFlash('error', 'Email non trouvé.');
                return $this->redirectToRoute('app_connect');
            }




            if (!$passwordHasher->isPasswordValid($admin, $plainPassword)) {
                $this->addFlash('error', 'Mot de passe incorrect.');
                return $this->redirectToRoute('app_connect');
            }

            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$user) {
                $this->addFlash('error', 'Utilisateur non trouvé.');
                return $this->redirectToRoute('app_connect');
            }

            $slug = $user->getSlug();

            $token = new UsernamePasswordToken(
                $admin,
                $plainPassword,
                ['main'],
                $admin->getRoles()
            );
            $tokenStorage->setToken($token);


            return $this->redirectToRoute('app_accueil', ['slug' => $slug]);
        }

        return $this->render('connect/index.html.twig', [
            'connectForm' => $form->createView()
        ]);
    }
}
