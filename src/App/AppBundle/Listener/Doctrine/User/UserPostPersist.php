<?php

namespace App\AppBundle\Listener\Doctrine\User;

use App\AppBundle\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class UserPostPersist {

    public function postPersist(LifecycleEventArgs $args) {
        $object = $args->getObject();
        if ($object instanceof User) {
            $this->updateReference($object);
            $args->getObjectManager()->flush();
        }
    }

    public function updateReference(User $user) {
        $ref = substr($user->getAccountNumber(), 0, 4).'-'.str_pad($user->getId(),10,'0',STR_PAD_LEFT);
        $user->setReference($ref);
    }

}