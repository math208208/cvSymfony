<?php

namespace App\Controller;

use App\Entity\ExperiencePro;
use App\Entity\ExperienceUni;
use App\Entity\Translation\ExperienceProTranslation;
use App\Entity\Translation\ExperienceUniTranslation;
use App\Entity\User;
use App\Repository\ExperienceProRepository;
use App\Repository\ExperienceUniRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

final class ExperienceController extends AbstractController
{
    #[Route('/{slug}/experiences', name: 'app_experience')]
    public function show(
        string $slug,
        UserRepository $userRepository,
        ExperienceProRepository $repoExpPro,
        ExperienceUniRepository $repoExpUni,
        TranslationService $translator,
        string $_locale,
        Security $security,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('VIEW_PROFILE', $slug);

        $user = $userRepository->findOneBySlug($slug);

        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        $experiencesPro = $repoExpPro->findByUser($user);
        foreach ($experiencesPro as $key => $experiencePro) {
            if ($experiencePro->isArchived()) {
                unset($experiencesPro[$key]);
            }
        }
        $experiencesUni = $repoExpUni->findByUser($user);
        foreach ($experiencesUni as $key => $experienceUni) {
            if ($experienceUni->isArchived()) {
                unset($experiencesUni[$key]);
            }
        }



        $translatedProfession = $translator->translate(
            User::class,
            $user->getId(),
            'profession',
            $user->getProfession() ?? 'Aucune donnée',
            $_locale
        );
        $translatedDescription = $translator->translate(
            User::class,
            $user->getId(),
            'description',
            $user->getDescription() ?? 'Aucune donnée',
            $_locale
        );

        $translatedUser = [
            'user' => $user,
            'translated_profession' => $translatedProfession,
            'translated_description' => $translatedDescription
        ];


        $translatedExperiencesPro = [];

        foreach ($experiencesPro as $experiencePro) {
            $translatedPoste = $translator->translate(
                ExperiencePro::class,
                $experiencePro->getId(),
                'poste',
                $experiencePro->getPoste() ?? 'Aucune donnée',
                $_locale
            );
            $translatedDescription = $translator->translate(
                ExperiencePro::class,
                $experiencePro->getId(),
                'description',
                $experiencePro->getDescription() ?? 'Aucune donnée',
                $_locale
            );


            $translatedExperiencesPro[] = [
                'experiencePro' => $experiencePro,
                'translated_poste' => $translatedPoste,
                'translated_description' => $translatedDescription
            ];
        }

        $translatedExperiencesUni = [];

        foreach ($experiencesUni as $experienceUni) {
            $translatedTitre = $translator->translate(
                ExperienceUni::class,
                $experienceUni->getId(),
                'titre',
                $experienceUni->getTitre() ?? 'Aucune donnée' ,
                $_locale
            );
            $translatedSousTitre = $translator->translate(
                ExperienceUni::class,
                $experienceUni->getId(),
                'sousTitre',
                $experienceUni->getSousTitre() ?? 'Aucune donnée',
                $_locale
            );
            $translatedDescription = $translator->translate(
                ExperienceUni::class,
                $experienceUni->getId(),
                'description',
                $experienceUni->getDescription() ?? 'Aucune donnée',
                $_locale
            );


            $translatedExperiencesUni[] = [
                'experienceUni' => $experienceUni,
                'translated_titre' => $translatedTitre,
                'translated_sousTitre' => $translatedSousTitre,
                'translated_description' => $translatedDescription
            ];
        }


        $admin = $security->getUser();
        /** @var \App\Entity\Admin $admin */
        $email = $admin->getEmail();
        $userCo = $userRepository->findOneBy(['email' => $email]);

        if ($this->isGranted('ROLE_PRO')) {
            $layout = 'base/pro/index.html.twig';
        } else if ($this->isGranted('ROLE_USER') || $this->isGranted('ROLE_ADMIN')) {
           
            if($slug===$userCo->getSlug()){
                $layout = 'base/user/index.html.twig';
            }else{
                $layout = 'base/user/explo.html.twig';
            }
            
        } 

        return $this->render(
            'experiences/index.html.twig',
            [
            'layout' => $layout,
            'user' => $translatedUser,
            'experiencesPro' => $translatedExperiencesPro,
            'experiencesUni' => $translatedExperiencesUni,
            'userCo'=>$userCo,
            ]
        );
    }
}
