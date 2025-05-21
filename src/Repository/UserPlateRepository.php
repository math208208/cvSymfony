<?php

namespace App\Repository;

use App\Entity\UserPlate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<UserPlate>
 */
class UserPlateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPlate::class);
    }

    public function findSuggestions(string $query): array
    {
        return $this->createQueryBuilder('u')
            ->select('DISTINCT u.userId, u.field, u.value')
            ->where('LOWER(u.value) LIKE :query')
            ->setParameter('query', '%' . strtolower($query) . '%')
            ->setMaxResults(30)
            ->getQuery()
            ->getScalarResult();
    }
}
