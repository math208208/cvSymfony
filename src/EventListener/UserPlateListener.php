<?php

namespace App\EventListener;

use App\Entity\User;
use App\Entity\UserPlate;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\EntityManagerInterface;

#[AsEntityListener(event: 'postPersist', entity: User::class)]
#[AsEntityListener(event: 'postUpdate', entity: User::class)]
class UserPlateListener
{
    public function postPersist(User $user, PostPersistEventArgs $args): void
    {
        $this->updateUserPlate($user, $args->getObjectManager());
    }

    public function postUpdate(User $user, PostUpdateEventArgs $args): void
    {
        $this->updateUserPlate($user, $args->getObjectManager());
    }

    private function updateUserPlate(User $user, EntityManagerInterface $em): void
    {
        // Supprime les anciennes données
        $em->createQuery('DELETE FROM App\Entity\UserPlate up WHERE up.userId = :userId')
            ->setParameter('userId', $user->getId())
            ->execute();

        $fields = [
            ['profession', $user->getProfession()],
            ['description', $user->getDescription()],
            ['nom', $user->getNom()],
            ['prenom', $user->getPrenom()],
        ];

        $i = 0;
        foreach ($fields as [$field, $value]) {
            if ($value) {
                $up = new UserPlate();
                $up->setUserId($user->getId());
                $up->setField($field);
                $up->setI(0);
                $up->setValue($value);
                $em->persist($up);
            }
        }

        // Champs multiples
        foreach ($user->getCompetences() as $index => $comp) {
            $up = new UserPlate();
            $up->setUserId($user->getId());
            $up->setField('competence');
            $up->setI($index);
            $up->setValue($comp->getNom());
            $em->persist($up);
        }

        foreach ($user->getOutils() as $index => $outil) {
            $up = new UserPlate();
            $up->setUserId($user->getId());
            $up->setField('outil');
            $up->setI($index);
            $up->setValue($outil->getNom());
            $em->persist($up);
        }

        foreach ($user->getLangues() as $index => $langue) {
            $up = new UserPlate();
            $up->setUserId($user->getId());
            $up->setField('langue');
            $up->setI($index);
            $up->setValue($langue->getNomLangue());
            $em->persist($up);
        }

        foreach ($user->getLoisirs() as $index => $loisir) {
            $up = new UserPlate();
            $up->setUserId($user->getId());
            $up->setField('loisir');
            $up->setI($index);
            $up->setValue($loisir->getNom());
            $em->persist($up);
        }

        foreach ($user->getExperiencesPro() as $index => $exp) {
            $up = new UserPlate();
            $up->setUserId($user->getId());
            $up->setField('experience_pro');
            $up->setI($index);
            $up->setValue($exp->getPoste() . ' - ' . $exp->getEntreprise());
            $em->persist($up);
        }

        foreach ($user->getExperiencesUni() as $index => $exp) {
            $up = new UserPlate();
            $up->setUserId($user->getId());
            $up->setField('experience_uni');
            $up->setI($index);
            $up->setValue($exp->getTitre());
            $em->persist($up);
        }

        foreach ($user->getFormations() as $index => $formation) {
            $up = new UserPlate();
            $up->setUserId($user->getId());
            $up->setField('formation');
            $up->setI($index);
            $up->setValue($formation->getIntitule());
            $em->persist($up);
        }

        $em->flush();
    }
}
