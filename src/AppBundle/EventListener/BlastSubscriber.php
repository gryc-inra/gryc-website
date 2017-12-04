<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Blast;
use AppBundle\Utils\TokenGenerator;
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
