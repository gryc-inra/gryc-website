<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Job;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BlastManager
{
    private $tokenGenerator;
    private $em;
    private $eventDispatcher;

    public function __construct(TokenGenerator $tokenGenerator, EntityManager $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createJob($data)
    {
        // Create a job token
        $token = $this->tokenGenerator->generateToken();

        // Add the job in database
        $job = new Job();
        $job->setName($token);
        $job->setFormData($data);

        // Persist the job in database
        $this->em->persist($job);
        $this->em->flush();

        // Call an event, to process the job in background
        $this->eventDispatcher->addListener(KernelEvents::TERMINATE, function (Event $event) use ($job) {
            // Launch the job
            $this->blast($job);
        });

        return $job;
    }

    private function blast(Job $job)
    {
        $formData = $job->getFormData();

        // Create a tempFile with the query
        $tmpQueryHandle = tmpfile();
        $metaDatas = stream_get_meta_data($tmpQueryHandle);
        $tmpQueryFilename = $metaDatas['uri'];
        fwrite($tmpQueryHandle, $formData->query);

        // Create a tempFile with results
        $tmpResults = tempnam('/tmp', $job->getName());

        $process = new Process('blastp -task blastp -query '.$tmpQueryFilename.' -db /blast/db/YALI -out '.$tmpResults.' -outfmt 5');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Add the file to the job, the status is automatically updated
        $job->setResult(file_get_contents($tmpResults));
        $this->em->flush();

        // Delete the temp files
        fclose($tmpQueryHandle);
        unlink($tmpResults);

        return;
    }
}
