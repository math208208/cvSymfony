<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ProfilController extends AbstractController
{

    #[Route('/{slug}/profil', name: 'app_profil')]
    public function show(
        string $slug,
        UserRepository $userRepository,
        TranslationService $translator,
        string $_locale,
    ): Response {
        $this->denyAccessUnlessGranted('VIEW_PROFILE', $slug);

        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $translatedProfession = $translator->translate(User::class, $user->getId(), 'profession', $user->getProfession() ?? 'Aucune donnée', $_locale);
        $translatedDescription = $translator->translate(User::class, $user->getId(), 'description', $user->getDescription() ?? 'Aucune donnée', $_locale);

        $translatedUser = [
            'user' => $user,
            'translated_profession' => $translatedProfession,
            'translated_description' => $translatedDescription
        ];

        return $this->render('profil/index.html.twig', [
            'user' => $translatedUser
        ]);
    }

    #[Route('/{slug}/profil/update', name: 'app_update_profil', methods: ['POST'])]
    public function update(
        Request $request,
        string $slug,
        UserRepository $userRepository,
        AdminRepository $adminRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        Security $security
    ): RedirectResponse {
        $etatProfil = $request->request->get('privateProfile');
        $newEmail = $request->request->get('email');
        $plainPassword = $request->request->get('plainPassword');

        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $oldEmail = $user->getEmail();
        $user->setEmail($newEmail);
        if ($etatProfil === null) {
            $user->setIsPrivate(false);
        } else {
            $user->setIsPrivate(true);
        }

        $admin = $adminRepository->findOneBy(['email' => $oldEmail]);
        if ($admin) {
            $admin->setEmail($newEmail);
            if ($plainPassword) {
                $admin->setPassword(
                    $passwordHasher->hashPassword($admin, $plainPassword)
                );
            }

            $em->flush();
        }

        $em->flush();

        $this->addFlash('success', 'Paramètres mis à jour avec succès.');

        return $this->redirectToRoute('app_profil', ['slug' => $slug]);
    }
}
