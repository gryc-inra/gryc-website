<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Utils\PasswordUpdater;
use AppBundle\Utils\TokenGenerator;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class UserSubscriber implements EventSubscriber
{
    private $passwordUpdater;
    private $tokenGenerator;

    public function __construct(PasswordUpdater $passwordUpdater, TokenGenerator $tokenGenerator)
    {
        $this->passwordUpdater = $passwordUpdater;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof User) {
            $this->createActivationToken($object);
            $this->passwordUpdater->encodePassword($object);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof User) {
            $this->passwordUpdater->encodePassword($object);
        }
    }

    private function createActivationToken(User $user)
    {
        // If it's not a new object, return
        if (null !== $user->getId()) {
            return;
        }

        $token = $this->tokenGenerator->generateToken();
        $user->setConfirmationToken($token);
    }
}
