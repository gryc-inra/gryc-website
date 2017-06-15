<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;

class BlastCleanerListener
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function onTerminate()
    {
        // In 10% of page load, we clean the old blast results
        if (rand(1, 100) <= 10) {
            // Get all expired blasts
            $blasts = $this->em->getRepository('AppBundle:Blast')->findExpiredBlasts();

            // Remove expired blasts
            foreach ($blasts as $blast) {
                $this->em->remove($blast);
            }

            // Persist in database
            $this->em->flush();
        }

        return;
    }
}
