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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

final class BlogController extends AbstractController
{
    #[Route('/{_locale}', name: 'app_blog')]
    public function show(
        UserRepository $userRepository,
        TranslationService $translator,
        PaginatorInterface $paginator,
        Request $request,
        Security $security,
        string $_locale
    ): Response {
        $searchTerm = $request->query->get('q', '');

        $qb = $userRepository->createQueryBuilder('u')
            ->where('u.isPrivate = false')
            ->andWhere('u.description IS NOT NULL')
            ->andWhere('u.profession IS NOT NULL');



        if (!empty($searchTerm)) {
            $qb
                ->andWhere('LOWER(u.nom) LIKE :searchTerm OR LOWER(u.prenom)
                 LIKE :searchTerm OR LOWER(u.profession) LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . strtolower($searchTerm) . '%');
        }

        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            8
        );


        $translatedUsers = [];

        foreach ($pagination as $user) {
            if ($user->getProfession() && $user->getDescription()) {
                $translatedProfession = $translator->translate(
                    User::class,
                    $user->getId(),
                    'profession',
                    $user->getProfession(),
                    $_locale
                );

                $translatedDescription = $translator->translate(
                    User::class,
                    $user->getId(),
                    'description',
                    $user->getDescription(),
                    $_locale
                );

                $translatedUsers[] = [
                    'user' => $user,
                    'translated_profession' => $translatedProfession,
                    'translated_description' => $translatedDescription,
                ];
            }
        }

        if ($this->isGranted('ROLE_PRO')) {
            $layout = 'base/pro/index.html.twig';
        } elseif ($this->isGranted('ROLE_USER') || $this->isGranted('ROLE_ADMIN')) {
            $admin = $security->getUser();
            /** @var \App\Entity\Admin $admin */
            $email = $admin->getEmail();
            $userSlug = $userRepository->findOneBy(['email' => $email]);


            $userTab = [
                'user' => $userSlug,

            ];
            $layout = 'base/user/index.html.twig';
            return $this->render('blog/index.html.twig', [
                'translatedUsers' => $translatedUsers,
                'pagination' => $pagination,
                'layout' => $layout,
                'user' => $userTab

            ]);
        } else {
            $layout = 'base/accueil/index.html.twig';
        }



        return $this->render('blog/index.html.twig', [
            'translatedUsers' => $translatedUsers,
            'pagination' => $pagination,
            'layout' => $layout

        ]);
    }
}
