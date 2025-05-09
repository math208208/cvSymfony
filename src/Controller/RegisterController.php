<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegisterController extends AbstractController
{
    #[Route(path: '/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ): Response {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                $form->addError(new FormError('Cet email est déjà utilisé.'));
            } else {
                $user = new User();
                $user->setNom($data['nom']);
                $user->setPrenom($data['prenom']);
                $user->setEmail($data['email']);
                $user->setTelephone($data['telephone']);
                $user->setIsPrivate($data['private']);

                $admin = new Admin();
                $admin->setEmail($data['email']);
                $admin->setRoles(['ROLE_USER']);
                $hashedPassword = $passwordHasher->hashPassword($admin, $data['plainPassword']);
                $admin->setPassword($hashedPassword);

                $em->persist($user);
                $em->persist($admin);
                $em->flush();

                $token = new UsernamePasswordToken($admin, 'main', $admin->getRoles());
                $tokenStorage->setToken($token);
                $session->set('_security_main', serialize($token));

                return $this->redirectToRoute('app_accueil', ['slug' => $user->getSlug()]);
            }
        }
        return $this->render(
            'register/index.html.twig',
            [
            'registerForm' => $form->createView()
            ]
        );
    }
}
