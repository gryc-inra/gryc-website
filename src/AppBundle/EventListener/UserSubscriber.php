<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Service\PasswordUpdater;
use AppBundle\Service\TokenGenerator;
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
