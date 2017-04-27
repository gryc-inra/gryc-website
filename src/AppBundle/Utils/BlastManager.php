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
        $blastType = $formData->blastType;
        $task = 'tblastx' === $blastType ? null : '-task '.$blastType;
        $evalue = $formData->evalue;
        if (true === $formData->filter) {
            if ('blastn' === $blastType) {
                $filter = '-dust yes';
            } else {
                $filter = '-seg yes';
            }
        } else {
            if ('blastn' === $blastType) {
                $filter = '-dust no';
            } else {
                $filter = '-seg no';
            }
        }

        // Create a tempFile with the query
        $tmpQueryHandle = tmpfile();
        $metaDatas = stream_get_meta_data($tmpQueryHandle);
        $tmpQueryFilename = $metaDatas['uri'];
        fwrite($tmpQueryHandle, $formData->query);

        // Create a tempFile with results
        $tmpResults = tempnam('/tmp', $job->getName());

        // blastn -task blastn -query fichier_query.fasta -db "path/db1 path/db2 path/db3" -out output.xml -outfmt 5 -evalue $evalue -num_threads 2
        $process = new Process($blastType.' '.$task.' -query '.$tmpQueryFilename.' -db /blast/db/YALI -out '.$tmpResults.' -outfmt 5 -evalue '.$evalue.' '.$filter.' -num_threads 2');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $job->setResult($process->getExitCode());
        } else {
            $job->setResult(file_get_contents($tmpResults));
        }

        // Add the file to the job, the status is automatically updated
        $this->em->flush();

        // Delete the temp files
        fclose($tmpQueryHandle);
        unlink($tmpResults);

        return;
    }
}
