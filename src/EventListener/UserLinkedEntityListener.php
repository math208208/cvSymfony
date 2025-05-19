<?php

namespace App\EventListener;

use App\Service\UserPlateUpdater;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserLinkedEntityListener
{
    private UserPlateUpdater $updater;

    public function __construct(UserPlateUpdater $updater)
    {
        $this->updater = $updater;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->handle($args->getObject());
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->handle($args->getObject());
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->handle($args->getObject());
    }

    private function handle(object $entity): void
    {
        if (method_exists($entity, 'getUser') && $entity->getUser()) {
            $user = $entity->getUser();
            $this->updater->refreshUserPlate($user->getId());
        }
    }
}
