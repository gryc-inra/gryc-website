<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Strain;
use Doctrine\ORM\Event\LifecycleEventArgs;
use FOS\ElasticaBundle\Persister\ObjectPersister;

class StrainListener
{
    private $objectPersister;
    private $memoryLimit;

    public function __construct(ObjectPersister $objectPersister, $memoryLimit) {
        $this->objectPersister = $objectPersister;
        $this->memoryLimit = $memoryLimit;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $strain = $args->getEntity();

        if (!$strain instanceof Strain) {
            return;
        }

        // Because we need to retrieve the complete Genome, we fix the memory limit on 512M
        ini_set('memory_limit', $this->memoryLimit);
        $em = $args->getEntityManager();
        $locusList = $em->getRepository('AppBundle:Locus')->findLocusFromStrain($strain);

        $this->objectPersister->replaceMany($locusList);

        return;
    }
}
