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

namespace AppBundle\Service;

use AppBundle\Entity\MultipleAlignment;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class MultipleAlignmentManager
{
    private $em;
    private $session;
    private $producer;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, ProducerInterface $producer)
    {
        $this->em = $em;
        $this->session = $session;
        $this->producer = $producer;
    }

    public function initAlignment(MultipleAlignment $multipleAlignment = null)
    {
        if (null === $multipleAlignment && null === $multipleAlignment = $this->getLastAlignment()) {
            $multipleAlignment = new MultipleAlignment();
        } else {
            $multipleAlignment = clone $multipleAlignment;
        }

        return $multipleAlignment;
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

    /**
     * Get alignment.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getAlignment($id)
    {
        return $this->em->getRepository('AppBundle:MultipleAlignment')->findOneById($id);
    }

    /**
     * Save Multiple alignment.
     *
     * @param MultipleAlignment $multipleAlignment
     *
     * @return MultipleAlignment
     */
    public function save(MultipleAlignment $multipleAlignment)
    {
        $this->em->persist($multipleAlignment);
        $this->em->flush();

        $this->producer->publish($multipleAlignment->getId());
        $this->session->set('last_multiple_alignment', $multipleAlignment->getId());

        return $multipleAlignment;
    }

    public function align($multipleAlignmentId)
    {
        $multipleAlignment = $this->getAlignment($multipleAlignmentId);

        // Set status on running
        $multipleAlignment->setStatus('running');
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

        $multipleAlignment->setCommandLine($process->getCommandLine());
        $multipleAlignment->setExitCode($process->getExitCode());

        $this->em->merge($multipleAlignment);
        $this->em->flush();

        // Delete the temp files
        fclose($tmpQueryHandle);

        return $multipleAlignment;
    }
}
