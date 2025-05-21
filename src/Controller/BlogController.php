<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserPlateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\ElasticService;
use App\Service\TranslationService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        string $_locale,
        ElasticService $elasticService,
    ): Response {
        $searchTerm = $request->query->get('q', '');
        $page = $request->query->getInt('page', 1); // récupère la page actuelle (par défaut 1)
        $size = 8;


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


        // // pagination
        // $users = $paginator->paginate(
        //     $qb->getQuery(),
        //     $page,
        //     $size
        // );
        // $translatedUsers = [];


        // foreach ($users as $user) {
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

        // $total = count($qb->getQuery()->getResult());
        // $totalPages = ceil($total / $size);

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
        //-------------------------------------------------------------





        // table plate
        //-------------------------------------------------------------
        $sql = '
            SELECT DISTINCT u.*
            FROM "user" u
            JOIN user_plate up ON up.user_id = u.id
            WHERE u.is_private = false
            AND u.description IS NOT NULL
            AND u.profession IS NOT NULL
        ';

        $params = [];

        if ($searchTerm) {
            $sql .= "
                AND LOWER(up.value) LIKE :searchTerm
            ";

            $params['searchTerm'] = '%' . strtolower($searchTerm) . '%';
        }

        // Exécution de la requête via DBAL
        $connection = $em->getConnection();
        $results = $connection->fetchAllAssociative($sql, $params);

        // Paginer le tableau résultat (pagination manuelle)
        $pagination = $paginator->paginate(
            $results,
            $page,
            $size
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

        $total = count($results);
        $totalPages = ceil($total / $size);

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


        // -------------------------------------------------------------



        // //ElasticSearch
        // //--------------------------------------------------------------
        // $params = [
        //     'index' => 'users',
        //     'body' => [
        //         'query' => [
        //             'bool' => [
        //                 'must' => [
        //                     ['term' => ['private' => false]],
        //                     ['exists' => ['field' => 'description']],
        //                     ['exists' => ['field' => 'profession']],
        //                 ],
        //             ],
        //         ],
        //         'size' => 8,
        //         'from' => ($page - 1) * $size,
        //     ],
        // ];

        // //prblm car elastic search recup pas tout donc lui
        // //considere que ce qui est recup cest juster pour une page
        // //donc jpeux pas utiliser kdn pour la pagination dans ce cas la

        // if ($searchTerm) {
        //     $params['body']['query']['bool']['must'][] = [
        //         'multi_match' => [
        //             'query' => $searchTerm,
        //             'fields' => ['nom^3', 'prenom^3', 'profession', 'formations',
        //             'experiences_pro', 'experiences_uni', 'langages', 'outils', 'loisirs', 'competences'],
        //             'fuzziness' => 'AUTO',
        //         ],
        //     ];
        // }

        // $results = $elasticService->search($params);
        // $hits = $results['hits']['hits'] ?? [];
        // $total = $results['hits']['total']['value'] ?? 0;
        // $totalPages = ceil($total / $size);

        // $translatedUsers = [];
        // foreach ($hits as $hit) {
        //     $userId = $hit['_id'];
        //     $userEntity = $userRepository->find($userId);

        //     if ($userEntity && $userEntity->getProfession() && $userEntity->getDescription()) {
        //         $translatedProfession = $translator->translate(
        //             User::class,
        //             $userId,
        //             'profession',
        //             $userEntity->getProfession(),
        //             $_locale
        //         );

        //         $translatedDescription = $translator->translate(
        //             User::class,
        //             $userId,
        //             'description',
        //             $userEntity->getDescription(),
        //             $_locale
        //         );

        //         $translatedUsers[] = [
        //             'user' => $userEntity,
        //             'translated_profession' => $translatedProfession,
        //             'translated_description' => $translatedDescription,
        //         ];
        //     }
        // }

        //---------------------------------------------------------------



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
                'layout' => $layout,
                'user' => $userTab,
                'page' => $page,
                'searchTerm' => $searchTerm,
                'totalPages' => $totalPages,

            ]);
        } else {
            $layout = 'base/accueil/index.html.twig';
        }



        return $this->render('blog/index.html.twig', [
            'translatedUsers' => $translatedUsers,
            'layout' => $layout,
            'page' => $page,
            'searchTerm' => $searchTerm,
            'totalPages' => $totalPages,
        ]);
    }


    #[Route('/autocomplete', name: 'autocomplete', methods: ['GET'])]
    public function index(Request $request, UserPlateRepository $repo, UserRepository $userRepo): JsonResponse
    {

        $q = $request->query->get('q', '');
        $results = $repo->findSuggestions($q);

        $suggestions = [];

        foreach ($results as $result) {
            $user = $userRepo->find($result['userId']);

            if ($user->isPrivate() === false) {
                $suggestions[] = [
                    'value' => $user->getNom() . ' ' . $user->getPrenom() . ' -> ' . $result["field"],
                    'slug' => $user->getSlug(),
                    'field' => $result["field"],
                ];
            }
        }



        return new JsonResponse($suggestions);
    }


    #[Route('/showcv/{id}', name: 'showcv', methods: ['GET'])]
    public function showcv(int $id, UserRepository $userRepo): JsonResponse
    {
        $user = $userRepo->find($id);

        $data = [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'profession' => $user->getProfession(),
            'description' => $user->getDescription(),
            'email' => $user->getEmail(),
            'telephone' => $user->getTelephone(),

            'outils' => array_map(fn($outil) => $outil->getNom(), $user->getOutils()->toArray()),


            'competences' => array_map(fn($competence) => $competence->getNom(), $user->getCompetences()->toArray()),
            'langues' => array_map(fn($langue) => [
                'nom' => $langue->getNomLangue(),
                'niveau' => $langue->getNiveau()
            ], $user->getLangues()->toArray()),

            'loisirs' => array_map(fn($loisir) => $loisir->getNom(), $user->getLoisirs()->toArray()),
            'experiencesPro' => array_map(fn($exp) => [
                'poste' => $exp->getPoste(),
                'entreprise' => $exp->getEntreprise(),
                'dateDebut' => $exp->getDateDebut(),
                'dateFin' => $exp->getDateFin() ?? null,
            ], $user->getExperiencesPro()->toArray()),

            'experiencesUni' => array_map(fn($exp) => [
                'titre' => $exp->getTitre(),
                'sousTitre' => $exp->getSousTitre(),
                'annee' => $exp->getAnnee(),
            ], $user->getExperiencesUni()->toArray()),

            'formations' => array_map(fn($formation) => [
                'intitule' => $formation->getIntitule(),
                'lieu' => $formation->getLieu(),
                'annee' => $formation->getAnnee(),
            ], $user->getFormations()->toArray()),
        ];

        return new JsonResponse($data);
    }
}
