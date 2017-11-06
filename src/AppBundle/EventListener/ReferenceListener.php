<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Reference;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ReferenceListener
{
    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if (!$object instanceof Reference) {
            return;
        }

        // If the Reference have no Locus and no Chromosomes, delete it
        if ($object->getLocus()->isEmpty() && $object->getChromosomes()->isEmpty()) {
            $args->getEntityManager()->remove($object);
            $args->getEntityManager()->flush();
        }
    }
}
