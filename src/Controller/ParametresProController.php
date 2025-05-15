<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\Langage;
use App\Entity\Loisir;
use App\Entity\Outil;
use App\Entity\User;
use App\Form\CompetencesType;
use App\Form\LangueType;
use App\Form\LoisirType;
use App\Form\OutilType;
use App\Repository\AdminRepository;
use App\Repository\MessageRepository;
use App\Repository\ProfessionnelRepository;
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

final class ParametresProController extends AbstractController
{
    #[Route('/parametres', name: 'app_parametresPro')]
    public function show(
        MessageRepository $messageRepository,
        ProfessionnelRepository $professionnelRepository,
        Security $security,
    ): Response {

        $admin = $security->getUser();
        /** @var \App\Entity\Admin $admin */
        $email = $admin->getEmail();
        $professionnel = $professionnelRepository->findOneBy(['email' => $email]);


        $messages = $messageRepository->findProByUser($professionnel);

        return $this->render(
            'parametres/pro/index.html.twig',
            [
                'messages' => $messages
            ]
        );
    }


    #[Route('/parametres/update', name: 'app_update_parametresPro', methods: ['POST'])]
    public function update(
        Request $request,
        ProfessionnelRepository $professionnelRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        Security $security
    ): RedirectResponse {
        $newEmail = $request->request->get('email');
        $plainPassword = $request->request->get('plainPassword');
        $newMetier = $request->request->get('metier');
        $newEntreprise = $request->request->get('entreprise');

        $admin = $security->getUser();
        /** @var \App\Entity\Admin $admin */
        $oldEmail = $admin->getEmail();
        $professionnel = $professionnelRepository->findOneBy(['email' => $oldEmail]);


        $admin->setEmail($newEmail);
        if ($plainPassword) {
            $admin->setPassword(
                $passwordHasher->hashPassword($admin, $plainPassword)
            );
        }

        $em->flush();


        if ($newEmail) {
            $professionnel->setEmail($newEmail);
        }

        if ($newMetier) {
            $professionnel->setMetier($newMetier);
        }

        if ($newEntreprise) {
            $professionnel->setEntreprise($newEntreprise);
        }



        $em->flush();

        return $this->redirectToRoute('app_parametresPro');
    }
}
