<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Job;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Process\Process;

class BlastManager
{
    private $tokenGenerator;
    private $em;
    private $eventDispatcher;
    private $twig;

    public function __construct(TokenGenerator $tokenGenerator, EntityManager $em, EventDispatcherInterface $eventDispatcher, \Twig_Environment $twig)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->twig = $twig;
    }

    public function createJob($data)
    {
        // Create a job token
        $token = $this->tokenGenerator->generateToken();

        // Before add the job in database, transform the Strains array collection in Ids array
        $strains = [];
        foreach ($data['strains'] as $strain) {
            $strains[] = $strain->getId();
        }
        $data['strains'] = $strains;

        // Add the job in database
        $job = new Job();
        $job->setName($token);
        $job->setFormData($data);

        // Persist the job in database
        $this->em->persist($job);
        $this->em->flush();

        // Call an event, to process the job in background
//        $this->eventDispatcher->addListener(KernelEvents::TERMINATE, function (Event $event) use ($job) {
            // Launch the job
            $this->blast($job);
//        });

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

        $db = '';
        foreach ($formData->strains as $strain) {
            $db .= ' /blast/db/'.$strain.'_'.$formData->database;
        }
        dump($db);

        // Create a tempFile with the query
        $tmpQueryHandle = tmpfile();
        $metaDatas = stream_get_meta_data($tmpQueryHandle);
        $tmpQueryFilename = $metaDatas['uri'];
        fwrite($tmpQueryHandle, $formData->query);

        // Create a tempFile with results
        $tmpResults = tempnam('/tmp', $job->getName());

        // blastn -task blastn -query fichier_query.fasta -db "path/db1 path/db2 path/db3" -out output.xml -outfmt 5 -evalue $evalue -num_threads 2
        $process = new Process($blastType.' '.$task.' -query '.$tmpQueryFilename.' -db '.$db.' -out '.$tmpResults.' -outfmt 5 -evalue '.$evalue.' '.$filter.' -num_threads 2');
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

    public function xmlToArray($xml)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent($xml);

        // Get the blast version
        $result['blast_version'] = $crawler->filterXPath('//BlastOutput/BlastOutput_version')->text();

        // For each Iteration
        $crawler->filterXPath('//BlastOutput/BlastOutput_iterations/Iteration')->each(function (Crawler $node) use (&$result) {
            // Init the count
            $i = isset($result['iterations']) ? count($result['iterations']) : 0;

            // Add params to the iteration
            $iteration['query_num'] = $node->filterXPath('//Iteration_iter-num')->text();
            $iteration['query_def'] = $node->filterXPath('//Iteration_query-def')->text();
            $iteration['query_len'] = $node->filterXPath('//Iteration_query-len')->text();

            // Get the grid step
            $queryLength = $iteration['query_len'];
            $gridLevel = [1, 5, 10, 25, 50, 100, 250, 500, 1000, 2000, 5000, 100000, 500000, 1000000];
            $gridStep = $gridLevel[0];

            $diff = abs($gridLevel[0] - $queryLength / 10);

            foreach ($gridLevel as $level) {
                if (abs($level - $queryLength / 10) < $diff) {
                    $gridStep = $level;
                }
            }

            $iteration['draw_grid_step'] = $gridStep;
            $iteration['draw_nb_steps'] = ceil($iteration['query_len'] / $gridStep);

            // Add the iteration to the result array
            $result['iterations'][$i] = $iteration;

            // For each Hit in Iteration
            $node->filterXPath('//Hit')->each(function (Crawler $node) use (&$result, $i) {
                // Init the count
                $j = isset($result['iterations'][$i]['hits']) ? count($result['iterations'][$i]['hits']) : 0;

                // Add param to the hit
                $hit['num'] = $node->filterXPath('//Hit_num')->text();
                $hit['id'] = $node->filterXPath('//Hit_id')->text();
                $queryDef = explode(' ', $node->filterXPath('//Hit_def')->text(), 2);
                $hit['name'] = $queryDef[0];
                $hit['desc'] = $queryDef[1];
                $hit['len'] = $node->filterXPath('//Hit_len')->text();

                // Add the hit to the result array
                $result['iterations'][$i]['hits'][$j] = $hit;

                // For each HSP in hit
                $node->filterXPath('//Hsp')->each(function (Crawler $node) use (&$result, $i, $j) {
                    // Init the count
                    $k = isset($result['iterations'][$i]['hits'][$j]['hsps']) ? count($result['iterations'][$i]['hits'][$j]['hsps']) : 0;

                    // Add param to the HSP
                    $hsp['num'] = $node->filterXPath('//Hsp_num')->text();
                    $hsp['bit_score'] = $node->filterXPath('//Hsp_bit-score')->text();
                    $hsp['evalue'] = $node->filterXPath('//Hsp_evalue')->text();
                    $hsp['query_from'] = $node->filterXPath('//Hsp_query-from')->text();
                    $hsp['query_to'] = $node->filterXPath('//Hsp_query-to')->text();
                    $hsp['hit_from'] = $node->filterXPath('//Hsp_hit-from')->text();
                    $hsp['hit_to'] = $node->filterXPath('//Hsp_hit-to')->text();
                    $hsp['identity'] = $node->filterXPath('//Hsp_identity')->text();
                    $hsp['gaps'] = $node->filterXPath('//Hsp_gaps')->text();
                    $hsp['align_len'] = $node->filterXPath('//Hsp_align-len')->text();
                    $hsp['qseq'] = str_split($node->filterXPath('//Hsp_qseq')->text(), 60);
                    $hsp['midline'] = str_split($node->filterXPath('//Hsp_midline')->text(), 60);
                    $hsp['hseq'] = str_split($node->filterXPath('//Hsp_hseq')->text(), 60);

                    // Add the HSP to result
                    $result['iterations'][$i]['hits'][$j]['hsps'][$k] = $hsp;
                });
            });
        });

        return $result;
    }
}
