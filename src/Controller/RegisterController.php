<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = new User();
            $user->setNom($data['nom']);
            $user->setPrenom($data['prenom']);
            $user->setEmail($data['email']);
            $user->setTelephone($data['telephone']);

            $admin = new Admin();
            $admin->setEmail($data['email']);
            $admin->setRoles(['ROLE_USER']);
            $hashedPassword = $passwordHasher->hashPassword($admin, $data['plainPassword']);
            $admin->setPassword($hashedPassword);

            $em->persist($user);
            $em->persist($admin);
            $em->flush();

            $this->addFlash('success', 'Compte créé avec succès !');



           
            return $this->redirectToRoute('app_accueil', ['slug' => $user -> getSlug()]);
        }

        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
