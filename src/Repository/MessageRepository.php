<?php

namespace App\Repository;

use App\Entity\Professionel;
use App\Entity\Message;
use App\Entity\Professionnel;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Professionel>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    //    /**
    //     * @return Professionel[] Returns an array of Professionel objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Professionel
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.receveur = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findProByUser(Professionnel $pro)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.expediteur = :pro')
            ->setParameter('pro', $pro)
            ->getQuery()
            ->getResult();
    }

}
