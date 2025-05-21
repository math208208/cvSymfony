<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // // Exemple de méthode pour récupérer les utilisateurs actifs (personnalisée)
    // public function findActiveUsers()
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.active = :active')
    //         ->setParameter('active', true)
    //         ->orderBy('u.id', 'ASC')
    //         ->getQuery()
    //         ->getResult();
    // }

    // // Exemple d'une méthode pour rechercher un utilisateur par son nom
    // public function findByName($name): array
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.Nom = :val')
    //         ->setParameter('val', $name)
    //         ->orderBy('u.id', 'ASC')
    //         ->getQuery()
    //         ->getResult();
    // }

    // // Méthode qui retourne un seul utilisateur par un champ spécifique
    // public function findOneByEmail($email): ?User
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.email = :email')
    //         ->setParameter('email', $email)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }

    public function findOneBySlug(string $slug): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findById(int $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
