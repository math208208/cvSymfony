<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\UserPlateUpdater;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;

class UserListener
{
    private UserPlateUpdater $updater;

    public function __construct(UserPlateUpdater $updater)
    {
        $this->updater = $updater;
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $user = $args->getObject();

        if (!$user instanceof User) {
            return;
        }

        $this->updater->refreshUserPlate($user->getId());
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $user = $args->getObject();

        if (!$user instanceof User) {
            return;
        }

        $this->updater->refreshUserPlate($user->getId());
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $user = $args->getObject();

        if (!$user instanceof User) {
            return;
        }

        $this->updater->deleteUserPlate($user->getId());
    }
}
