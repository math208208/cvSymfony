<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Professionnel;
use App\Form\RegisterProType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\PreDec;
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
        $formPro = $this->createForm(RegisterProType::class);
        $formPro->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            $existingAdmin = $em->getRepository(Admin::class)->findOneBy(['email' => $data['email']]);

            if ($existingUser || $existingAdmin) {
                $form->addError(new FormError('Cet email est déjà utilisé.'));
            } else {
                $admin = new Admin();
                $admin->setEmail($data['email']);
                $admin->setRoles(['ROLE_USER']);
                $hashedPassword = $passwordHasher->hashPassword($admin, $data['plainPassword']);
                $admin->setPassword($hashedPassword);

                $em->persist($admin);
                $em->flush();

                $adminCreer = $em->getRepository(Admin::class)->findOneBy(['email' => $data['email']]);
                $user = new User();
                if ($adminCreer) {
                    $user->setNom($data['nom']);
                    $user->setPrenom($data['prenom']);
                    $user->setEmail($data['email']);
                    $user->setTelephone($data['telephone']);
                    $user->setIsPrivate($data['private']);

                    $em->persist($user);
                    $em->flush();
                    $token = new UsernamePasswordToken($admin, 'main', $admin->getRoles());
                    $tokenStorage->setToken($token);
                    $session->set('_security_main', serialize($token));
                } else {
                    $form->addError(new FormError('Probleme dans la création'));
                }

                return $this->redirectToRoute('app_profil', ['slug' => $user->getSlug()]);
            }
        } elseif ($formPro->isSubmitted() && $formPro->isValid()) {
            $data = $formPro->getData();

            $existingPro = $em->getRepository(Professionnel::class)->findOneBy(['email' => $data['email']]);
            $existingAdmin = $em->getRepository(Admin::class)->findOneBy(['email' => $data['email']]);

            if ($existingPro || $existingAdmin) {
                $formPro->addError(new FormError('Cet email est déjà utilisé.'));
            } else {
                $admin = new Admin();
                $admin->setEmail($data['email']);
                $admin->setRoles(['ROLE_PRO']);
                $hashedPassword = $passwordHasher->hashPassword($admin, $data['plainPassword']);
                $admin->setPassword($hashedPassword);

                $em->persist($admin);
                $em->flush();

                $adminCreer = $em->getRepository(Admin::class)->findOneBy(['email' => $data['email']]);
                if ($adminCreer) {
                    $professionnel = new Professionnel();
                    $professionnel->setNom($data['nom']);
                    $professionnel->setPrenom($data['prenom']);
                    $professionnel->setEmail($data['email']);
                    $professionnel->setMetier($data['metier']);
                    $professionnel->setEntreprise($data['entreprise']);

                    $em->persist($professionnel);
                    $em->flush();
                    $token = new UsernamePasswordToken($admin, 'main', $admin->getRoles());
                    $tokenStorage->setToken($token);
                    $session->set('_security_main', serialize($token));
                } else {
                    $formPro->addError(new FormError('Probleme dans la création'));
                }




                return $this->redirectToRoute('app_parametresPro');  //gerer le route
            }
        }
        return $this->render(
            'register/index.html.twig',
            [
                'registerForm' => $form->createView(),
                'registerProForm' => $formPro->createView()

            ]
        );
    }
}
