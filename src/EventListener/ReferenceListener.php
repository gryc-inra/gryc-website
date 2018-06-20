<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
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

namespace App\EventListener;

use App\Entity\Reference;
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
