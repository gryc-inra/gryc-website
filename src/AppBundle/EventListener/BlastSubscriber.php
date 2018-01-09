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

use AppBundle\Entity\Blast;
use AppBundle\Service\TokenGenerator;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BlastSubscriber implements EventSubscriber
{
    private $tokenGenerator;
    private $producer;
    private $session;

    public function __construct(TokenGenerator $tokenGenerator, ProducerInterface $producer, SessionInterface $session)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->producer = $producer;
        $this->session = $session;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'postPersist',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if (!$object instanceof Blast) {
            return;
        }

        $token = $this->tokenGenerator->generateToken();
        $object->setName($token);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if (!$object instanceof Blast) {
            return;
        }

        // Publish in Messaging Queue
        $this->producer->publish($object->getId());

        // Set at last Blast in User session
        $this->session->set('last_blast', $object->getId());
    }
}
