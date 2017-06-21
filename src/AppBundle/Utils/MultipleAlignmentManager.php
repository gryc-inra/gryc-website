<?php

namespace AppBundle\Utils;

use AppBundle\Entity\MultipleAlignment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class MultipleAlignmentManager
{
    private $em;
    private $projectDir;
    private $session;

    public function __construct(EntityManager $em, $projectDir, Session $session)
    {
        $this->em = $em;
        $this->projectDir = $projectDir;
        $this->session = $session;
    }

    public function getLastAlignment()
    {
        $lastMultipleAlignmentId = $this->session->get('last_multiple_alignment');
        $multipleAlignment = $this->em->getRepository('AppBundle:MultipleAlignment')->findOneById($lastMultipleAlignmentId);

        if (null !== $multipleAlignment) {
            $multipleAlignment = clone $multipleAlignment;
        } else {
            $multipleAlignment = new MultipleAlignment();
        }

        return $multipleAlignment;
    }

    public function align($multipleAlignmentId)
    {
        $multipleAlignment = $this->em->getRepository('AppBundle:MultipleAlignment')->findOneById($multipleAlignmentId);

        $multipleAlignment->setStatus('running');
        $this->em->merge($multipleAlignment);
        $this->em->flush();

        // Create a tempFile with the query
        $tmpQueryHandle = tmpfile();
        $metaDatas = stream_get_meta_data($tmpQueryHandle);
        $tmpQueryFilename = $metaDatas['uri'];
        fwrite($tmpQueryHandle, $multipleAlignment->getQuery());

        //  mafft --auto --thread 2 path/to/query
        $process = new Process('mafft --auto --thread 2 '.$tmpQueryFilename);

        // fix a timeout on 20 secs
        $process->setTimeout(20);

        try {
            $process->run();
        } catch (RuntimeException $exception) {
            $multipleAlignment->setStatus('failed');
            $multipleAlignment->setErrorOutput($process->getErrorOutput());
        }

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $multipleAlignment->setStatus('failed');
            $multipleAlignment->setErrorOutput($process->getErrorOutput());
        } else {
            $multipleAlignment->setStatus('finished');
            $multipleAlignment->setOutput($process->getOutput());
        }

        $multipleAlignment->setExitCode($process->getExitCode());

        $this->em->merge($multipleAlignment);
        $this->em->flush();

        // Delete the temp files
        fclose($tmpQueryHandle);

        return $multipleAlignment;
    }
}
