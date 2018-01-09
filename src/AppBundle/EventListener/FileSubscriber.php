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

namespace AppBundle\EventListener;

use AppBundle\Entity\File;
use AppBundle\Service\FileManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class FileSubscriber implements EventSubscriber
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * FileSubscriber constructor.
     *
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * Declare events subscribed by the subscriber.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postPersist',
            'postUpdate',
            'preRemove',
            'postRemove',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!is_subclass_of($object, File::class)) {
            return;
        }

        // Create the fileName (path)
        $this->fileManager->prepareMoveFile($object);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!is_subclass_of($object, File::class)) {
            return;
        }

        // Create the fileName (path)
        $this->fileManager->prepareMoveFile($object);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!is_subclass_of($object, File::class)) {
            return;
        }

        // Move the file
        $this->fileManager->moveFile($object);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!is_subclass_of($object, File::class)) {
            return;
        }

        // Move the file
        $this->fileManager->moveFile($object);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!is_subclass_of($object, File::class)) {
            return;
        }

        // Store the file path in tempPath
        $this->fileManager->prepareRemoveFile($object);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!is_subclass_of($object, File::class)) {
            return;
        }

        // Remove the file
        $this->fileManager->removeFile($object);
    }
}
