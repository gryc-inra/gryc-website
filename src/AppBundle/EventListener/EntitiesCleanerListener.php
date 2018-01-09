<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;

class EntitiesCleanerListener
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
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
    }
}
