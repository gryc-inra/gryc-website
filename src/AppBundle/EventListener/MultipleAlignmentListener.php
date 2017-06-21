<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\MultipleAlignment;
use AppBundle\Utils\TokenGenerator;
use Doctrine\ORM\Event\LifecycleEventArgs;

class MultipleAlignmentListener
{
    private $tokenGenerator;

    public function __construct(TokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if (!$object instanceof MultipleAlignment) {
            return;
        }

        $token = $this->tokenGenerator->generateToken();
        $object->setName($token);
    }
}
