<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Strain;
use Doctrine\ORM\Event\LifecycleEventArgs;
use FOS\ElasticaBundle\Persister\ObjectPersister;

class StrainListener
{
    private $objectPersister;

    public function __construct(ObjectPersister $objectPersister) {
        $this->objectPersister = $objectPersister;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $strain = $args->getEntity();

        if (!$strain instanceof Strain) {
            return;
        }

        $em = $args->getEntityManager();
        $locusList = $em->getRepository('AppBundle:Locus')->findLocusFromStrain($strain);

        $this->objectPersister->replaceMany($locusList);

        return;
    }
}
