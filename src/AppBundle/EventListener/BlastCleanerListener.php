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
            // Get all expired jobs
            $jobs = $this->em->getRepository('AppBundle:Job')->findExpiredJobs();

            // Remove expired jobs
            foreach ($jobs as $job) {
                $this->em->remove($job);
            }

            // Persist in database
            $this->em->flush();
        }

        return;
    }
}
