<?php

namespace App\Controller;

use App\Entity\ExperiencePro;
use App\Entity\ExperienceUni;
use App\Entity\User;
use App\Repository\ExperienceProRepository;
use App\Repository\ExperienceUniRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;

final class BlogController extends AbstractController
{
    #[Route('/{slug}/blog', name: 'app_blog')]
    public function show(
        string $slug,
        UserRepository $userRepository,
        ExperienceProRepository $repoExpPro,
        ExperienceUniRepository $repoExpUni,
        TranslationService $translator,
        string $_locale
    ): Response {
        $this->denyAccessUnlessGranted('VIEW_PROFILE', $slug);

        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
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



        $experiencesProAll = $repoExpPro->findAll();

        foreach ($experiencesProAll as $key => $experiencePro) {
            if (
                $experiencePro->isArchived() || $experiencePro->getUser()->getSlug() === $slug
                || $experiencePro->getUser()->isPrivate() === true
            ) {
                unset($experiencesProAll[$key]);
            }
        }


        $experiencesUniAll = $repoExpUni->findAll();

        foreach ($experiencesUniAll as $key => $experienceUni) {
            if (
                $experienceUni->isArchived() || $experienceUni->getUser()->getSlug() === $slug
                || $experienceUni->getUser()->isPrivate() === true
            ) {
                unset($experiencesUniAll[$key]);
            }
        }



        $translatedExperiencesProAll = [];

        foreach ($experiencesProAll as $experiencePro) {
            $translatedPoste = $translator->translate(
                ExperiencePro::class,
                $experiencePro->getId(),
                'poste',
                $experiencePro->getPoste(),
                $_locale
            );
            $translatedDescription = $translator->translate(
                ExperiencePro::class,
                $experiencePro->getId(),
                'description',
                $experiencePro->getDescription(),
                $_locale
            );


            $translatedExperiencesProAll[] = [
                'experiencePro' => $experiencePro,
                'translated_poste' => $translatedPoste,
                'translated_description' => $translatedDescription
            ];
        }

        $translatedExperiencesUniAll = [];

        foreach ($experiencesUniAll as $experienceUni) {
            $translatedTitre = $translator->translate(
                ExperienceUni::class,
                $experienceUni->getId(),
                'titre',
                $experienceUni->getTitre(),
                $_locale
            );
            $translatedSousTitre = $translator->translate(
                ExperienceUni::class,
                $experienceUni->getId(),
                'sousTitre',
                $experienceUni->getSousTitre(),
                $_locale
            );
            $translatedDescription = $translator->translate(
                ExperienceUni::class,
                $experienceUni->getId(),
                'description',
                $experienceUni->getDescription(),
                $_locale
            );


            $translatedExperiencesUniAll[] = [
                'experienceUni' => $experienceUni,
                'translated_titre' => $translatedTitre,
                'translated_sousTitre' => $translatedSousTitre,
                'translated_description' => $translatedDescription
            ];
        }


        return $this->render(
            'blog/index.html.twig',
            [
            'user' => $translatedUser,
            'exploExpPro' => $translatedExperiencesProAll,
            'exploExpUni' => $translatedExperiencesUniAll

            ]
        );
    }
}
