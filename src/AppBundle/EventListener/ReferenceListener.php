<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Reference;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ReferenceListener
{
    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if (!$object instanceof Reference) {
            return;
        }

        // If the Reference have no Locus and no Strains, delete it
        if ($object->getLocus()->isEmpty() && $object->getStrains()->isEmpty()) {
            $args->getEntityManager()->remove($object);
            $args->getEntityManager()->flush();
        }
    }
}
