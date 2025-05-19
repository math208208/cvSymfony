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
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

final class BlogController extends AbstractController
{
    #[Route('/{_locale}', name: 'app_blog')]
    public function show(
        UserRepository $userRepository,
        TranslationService $translator,
        PaginatorInterface $paginator,
        Request $request,
        Security $security,
        EntityManagerInterface $em,
        string $_locale
    ): Response {
        $searchTerm = $request->query->get('q', '');


        // Classique
        //-------------------------------------------------------------


        // $qb = $userRepository->createQueryBuilder('u')
        //     ->leftJoin('u.competences', 'c')
        //     ->leftJoin('u.outils', 'o')
        //     ->leftJoin('u.langues', 'l')
        //     ->leftJoin('u.loisirs', 'lo')
        //     ->leftJoin('u.experiencesUni', 'eu')
        //     ->leftJoin('u.experiencesPro', 'ep')
        //     ->distinct()
        //     ->leftJoin('u.formations', 'f')
        //     ->where('u.isPrivate = false')
        //     ->andWhere('u.description IS NOT NULL')
        //     ->andWhere('u.profession IS NOT NULL');


        // if (!empty($searchTerm)) {
        //     $qb
        //         ->andWhere('LOWER(u.nom) LIKE :searchTerm
        //                 OR LOWER(u.prenom) LIKE :searchTerm
        //                 OR LOWER(u.profession) LIKE :searchTerm
        //                 OR LOWER(c.nom) LIKE :searchTerm               
        //                 OR LOWER(o.nom) LIKE :searchTerm
        //                 OR LOWER(l.nomLangue) LIKE :searchTerm
        //                 OR LOWER(lo.nom) LIKE :searchTerm
        //                 OR LOWER(eu.titre) LIKE :searchTerm
        //                 OR LOWER(ep.poste) LIKE :searchTerm
        //                 OR LOWER(ep.entreprise) LIKE :searchTerm')
        //         ->setParameter('searchTerm', '%' . strtolower($searchTerm) . '%');
        // }


        //         //classique SQL 
        //         EXPLAIN ANALYSE SELECT *
        // FROM "user" u
        // LEFT JOIN experience_pro ep ON ep.user_id = u.id
        // LEFT JOIN experience_uni eu ON eu.user_id = u.id
        // LEFT JOIN formation f ON f.user_id = u.id
        // LEFT JOIN loisir lo ON lo.user_id = u.id
        // LEFT JOIN langage l ON l.user_id = u.id
        // LEFT JOIN competence c ON c.user_id = u.id
        // LEFT JOIN outil o ON o.user_id = u.id
        // WHERE u.is_private = false
        //   AND u.description IS NOT NULL
        //   AND u.profession IS NOT NULL
        //   AND (
        //     LOWER(u.nom) LIKE '%développeur%' OR
        //     LOWER(u.prenom) LIKE '%développeur%' OR
        //     LOWER(u.profession) LIKE '%développeur%' OR
        //     LOWER(c.nom) LIKE '%développeur%' OR
        //     LOWER(o.nom) LIKE '%développeur%' OR
        //     LOWER(l.nom_langue) LIKE '%développeur%' OR
        //     LOWER(lo.nom) LIKE '%développeur%' OR
        //     LOWER(eu.titre) LIKE '%développeur%' OR
        //     LOWER(ep.poste) LIKE '%développeur%' OR
        //     LOWER(ep.entreprise) LIKE '%développeur%'
        //   );

        //pagination
        // $pagination = $paginator->paginate(
        //     $qb->getQuery(),
        //     $request->query->getInt('page', 1),
        //     8
        // );

        // $translatedUsers = [];

        // foreach ($pagination as $user) {
        //     if ($user->getProfession() && $user->getDescription()) {
        //         $translatedProfession = $translator->translate(
        //             User::class,
        //             $user->getId(),
        //             'profession',
        //             $user->getProfession(),
        //             $_locale
        //         );

        //         $translatedDescription = $translator->translate(
        //             User::class,
        //             $user->getId(),
        //             'description',
        //             $user->getDescription(),
        //             $_locale
        //         );

        //         $translatedUsers[] = [
        //             'user' => $user,
        //             'translated_profession' => $translatedProfession,
        //             'translated_description' => $translatedDescription,
        //         ];
        //     }
        // }
        //-------------------------------------------------------------





        //table plate
        //-------------------------------------------------------------
        $sql = "SELECT * FROM user_plate
                WHERE is_private = false
                  AND description IS NOT NULL
                  AND profession IS NOT NULL";

        $params = [];

        if ($searchTerm) {
            $sql .= " AND (
                LOWER(nom) LIKE :searchTerm
                OR LOWER(prenom) LIKE :searchTerm
                OR LOWER(profession) LIKE :searchTerm
                OR LOWER(formations) LIKE :searchTerm
                OR LOWER(experiences_pro) LIKE :searchTerm
                OR LOWER(experiences_uni) LIKE :searchTerm
                OR LOWER(langages) LIKE :searchTerm
                OR LOWER(outils) LIKE :searchTerm
                OR LOWER(loisirs) LIKE :searchTerm
                OR LOWER(competences) LIKE :searchTerm
            )";

            $params['searchTerm'] = '%' . strtolower($searchTerm) . '%';
        }

        // Exécution de la requête via DBAL
        $connection = $em->getConnection();
        $results = $connection->fetchAllAssociative($sql, $params);

        // Paginer le tableau résultat (pagination manuelle)
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            8
        );

        //traduction avec table plate
        $translatedUsers = [];
        foreach ($pagination as $userData) {
            $userEntity = $userRepository->find($userData['id']);

            if ($userEntity && $userEntity->getProfession() && $userEntity->getDescription()) {
                $translatedProfession = $translator->translate(
                    User::class,
                    $userEntity->getId(),
                    'profession',
                    $userEntity->getProfession(),
                    $_locale
                );

                $translatedDescription = $translator->translate(
                    User::class,
                    $userEntity->getId(),
                    'description',
                    $userEntity->getDescription(),
                    $_locale
                );

                $translatedUsers[] = [
                    'user' => $userEntity,
                    'translated_profession' => $translatedProfession,
                    'translated_description' => $translatedDescription,
                ];
            }
        }



        //         EXPLAIN ANALYSE SELECT *
        // FROM user_plate
        // WHERE 
        //     LOWER(nom) LIKE LOWER('%infostrates%')
        //     OR LOWER(prenom) LIKE LOWER('%infostrates%')
        //     OR LOWER(profession) LIKE LOWER('%infostrates%')
        //     OR LOWER(formations) LIKE LOWER('%infostrates%')
        //     OR LOWER(experiences_pro) LIKE LOWER('%infostrates%')
        //     OR LOWER(experiences_uni) LIKE LOWER('%infostrates%')
        //     OR LOWER(langages) LIKE LOWER('%infostrates%')
        //     OR LOWER(outils) LIKE LOWER('%infostrates%')
        //     OR LOWER(loisirs) LIKE LOWER('%infostrates%')
        //     OR LOWER(competences) LIKE LOWER('%infostrates%')
        // ;
        //-------------------------------------------------------------










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
