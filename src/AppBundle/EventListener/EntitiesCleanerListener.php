<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;

class EntitiesCleanerListener
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function onTerminate()
    {
        // In 10% of page load, we clean the old results
        if (rand(1, 100) <= 10) {
            // Get all expired entities
            $blasts = $this->em->getRepository('AppBundle:Blast')->findExpired();
            $multipleAlignments = $this->em->getRepository('AppBundle:MultipleAlignment')->findExpired();

            // Remove expired blasts
            foreach ($blasts as $blast) {
                $this->em->remove($blast);
            }

            // Remove expired multiple alignments
            foreach ($multipleAlignments as $multipleAlignment) {
                $this->em->remove($multipleAlignment);
            }

            // Persist in database
            $this->em->flush();
        }

        return;
    }
}
