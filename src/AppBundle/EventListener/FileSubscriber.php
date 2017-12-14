<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\File;
use AppBundle\Utils\FileManager;
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
