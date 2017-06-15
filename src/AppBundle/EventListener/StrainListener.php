<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Strain;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use FOS\ElasticaBundle\Persister\ObjectPersister;

class StrainListener
{
    private $objectPersister;

    public function __construct(ObjectPersister $objectPersister)
    {
        $this->objectPersister = $objectPersister;
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $strain = $args->getEntity();

        if (!$strain instanceof Strain) {
            return;
        }

        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        // Check if the public or authorizedUsers fields have been updated
        // Per dafault, fix on false
        $updateElasticsearch = false;

        // If the public fields is updated: true
        if (true === $args->hasChangedField('public')) {
            $updateElasticsearch = true;
        // For the authorizedUsers, it's mor complicated, because it's a ManyToMany relation
        } else {
            // Retrieve the collection
            foreach ($uow->getScheduledCollectionUpdates() as $entityScheduledUpdates) {
                // For each object in the collection, test the fieldName
                // If one of them is authorizedUsers, at least on user have changed, set on true, and break the loop
                if ('authorizedUsers' === $entityScheduledUpdates->getMapping()['fieldName']) {
                    $updateElasticsearch = true;
                    break;
                }
            }
        }

        // If we need to update the elasticsearch index, do it
        if (true === $updateElasticsearch) {
            $locusList = $em->getRepository('AppBundle:Locus')->findLocusFromStrain($strain);

            $this->objectPersister->replaceMany($locusList);
        }

        return;
    }
}
