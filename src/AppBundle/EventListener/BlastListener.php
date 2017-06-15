<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Blast;
use AppBundle\Utils\TokenGenerator;
use Doctrine\ORM\Event\LifecycleEventArgs;

class BlastListener
{
    private $tokenGenerator;

    public function __construct(TokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
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
}
