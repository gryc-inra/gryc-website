<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Blast;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class BlastManager
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

    public function initBlast(Blast $blast = null)
    {
        if (null === $blast && null === $blast = $this->getLastBlast()) {
            $blast = new Blast();
        } else {
            // Here, clone the Blast give by the user, or the last blast (In the if, we define blast and test it after the &&)
            $blast = clone $blast;
        }

        return $blast;
    }

    public function getLastBlast()
    {
        $lastBlastId = $this->session->get('last_blast');
        $blast = $this->em->getRepository('AppBundle:Blast')->findOneById($lastBlastId);

        if (null !== $blast) {
            $blast = clone $blast;
        } else {
            $blast = new Blast();
        }

        return $blast;
    }

    public function blast($blastId)
    {
        $blast = $this->em->getRepository('AppBundle:Blast')->findToBlast($blastId);

        $tool = $blast->getTool();

        // Define blast options
        // The task
        $task = 'tblastx' === $tool ? null : '-task '.$tool;

        // The evalue
        $evalue = $blast->getEvalue();

        // The filter
        if (true === $blast->getFilter()) {
            if ('blastn' === $tool) {
                $filter = '-dust yes';
            } else {
                $filter = '-seg yes';
            }
        } else {
            if ('blastn' === $tool) {
                $filter = '-dust no';
            } else {
                $filter = '-seg no';
            }
        }

        // The gaps
        if (true === $blast->getGapped()) {
            $gapped = '';
        } else {
            $gapped = '-ungapped';
        }

        // Get the DBs addresses
        $db = '';
        foreach ($blast->getStrains() as $strain) {
            $db .= ' '.$this->projectDir.'/files/blast/'.$strain->getId().'_'.$blast->getDatabase();
        }

        // Create a tempFile with the query
        $tmpQueryHandle = tmpfile();
        $metaDatas = stream_get_meta_data($tmpQueryHandle);
        $tmpQueryFilename = $metaDatas['uri'];
        fwrite($tmpQueryHandle, $blast->getQuery());

        // blastn -task blastn -query fichier_query.fasta -db "path/db1 path/db2 path/db3" -out output.xml -outfmt 5 -evalue $evalue -num_threads 2
        $process = new Process($tool.' '.$task.' -query '.$tmpQueryFilename.' -db "'.$db.'" -outfmt 5 -max_target_seqs 50 -max_hsps 30 -evalue '.$evalue.' '.$filter.' '.$gapped.' -num_threads 2');

        // fix a timeout on 2 mins
        set_time_limit(130);
        $process->setTimeout(120);

        try {
            $process->run();
        } catch (RuntimeException $exception) {
            $blast->setStatus('failed');
            $blast->setErrorOutput($process->getErrorOutput());
        }

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $blast->setStatus('failed');
            $blast->setErrorOutput($process->getErrorOutput());
        } else {
            $blast->setStatus('finished');
            $blast->setOutput($process->getOutput());
        }

        $blast->setExitCode($process->getExitCode());

        $this->em->merge($blast);
        $this->em->flush();

        // Delete the temp files
        fclose($tmpQueryHandle);

        return $blast;
    }

    public function xmlToArray(Blast $blast)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent($blast->getOutput());

        // Get the blast version
        $result['blast_version'] = $crawler->filterXPath('//BlastOutput/BlastOutput_version')->text();
        $result['blast_tool'] = strtolower(explode(' ', $result['blast_version'])[0]);

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
            $result['iterations'][$i]['hits'] = [];
            $node->filterXPath('//Hit')->each(function (Crawler $node) use (&$result, $i) {
                // Init the count
                $j = isset($result['iterations'][$i]['hits']) ? count($result['iterations'][$i]['hits']) : 0;

                // Add param to the hit
                $hit['num'] = $node->filterXPath('//Hit_num')->text();
                $hit['id'] = $node->filterXPath('//Hit_id')->text();
                $queryDef = explode(' ', $node->filterXPath('//Hit_def')->text(), 2);
                $hit['name'] = $queryDef[0];
                $hit['desc'] = isset($queryDef[1]) ? $queryDef[1] : null;
                $hit['len'] = $node->filterXPath('//Hit_len')->text();

                // Add the hit to the result array
                $result['iterations'][$i]['hits'][$j] = $hit;

                // For each HSP in hit
                $previousDrawedHSPQueryFrom = 0;
                $previousDrawedHSPQueryTo = 0;
                $node->filterXPath('//Hsp')->each(function (Crawler $node) use (&$result, $i, $j, &$previousDrawedHSPQueryFrom, &$previousDrawedHSPQueryTo) {
                    // Init the count
                    $k = isset($result['iterations'][$i]['hits'][$j]['hsps']) ? count($result['iterations'][$i]['hits'][$j]['hsps']) : 0;

                    // Add param to the HSP
                    $hsp['num'] = $node->filterXPath('//Hsp_num')->text();
                    $hsp['bit_score'] = $node->filterXPath('//Hsp_bit-score')->text();
                    $hsp['score'] = $node->filterXPath('//Hsp_score')->text();
                    $hsp['evalue'] = $node->filterXPath('//Hsp_evalue')->text();
                    $hsp['query_from'] = $node->filterXPath('//Hsp_query-from')->text();
                    $hsp['query_to'] = $node->filterXPath('//Hsp_query-to')->text();
                    $hsp['hit_from'] = $node->filterXPath('//Hsp_hit-from')->text();
                    $hsp['hit_to'] = $node->filterXPath('//Hsp_hit-to')->text();
                    $hsp['query_frame'] = $node->filterXPath('//Hsp_query-frame')->text();
                    $hsp['hit_frame'] = $node->filterXPath('//Hsp_hit-frame')->text();
                    $hsp['identity'] = $node->filterXPath('//Hsp_identity')->text();
                    $hsp['positive'] = $node->filterXPath('//Hsp_positive')->text();
                    $hsp['gaps'] = $node->filterXPath('//Hsp_gaps')->text();
                    $hsp['align_len'] = $node->filterXPath('//Hsp_align-len')->text();
                    $hsp['qseq'] = str_split($node->filterXPath('//Hsp_qseq')->text(), 60);
                    $hsp['midline'] = str_split($node->filterXPath('//Hsp_midline')->text(), 60);
                    $hsp['hseq'] = str_split($node->filterXPath('//Hsp_hseq')->text(), 60);

                    // Adapt the alignment, to be displayed directly in twig
                    $hsp = $this->createAlignment($hsp, $result['blast_tool']);

                    // Draw or not the HSP on the graphic ?
                    // if the hsp coordinate are in the previous hsp coordinate range, do not draw
                    if (
                       ($hsp['query_from'] > $previousDrawedHSPQueryFrom && $hsp['query_from'] < $previousDrawedHSPQueryTo)
                    || ($hsp['query_to'] > $previousDrawedHSPQueryFrom && $hsp['query_to'] < $previousDrawedHSPQueryTo)
                    ) {
                        $hsp['draw'] = false;
                    } else {
                        $hsp['draw'] = true;
                        $previousDrawedHSPQueryFrom = $hsp['query_from'];
                        $previousDrawedHSPQueryTo = $hsp['query_to'];
                    }

                    // Add the HSP to result
                    $result['iterations'][$i]['hits'][$j]['hsps'][$k] = $hsp;
                });
            });
        });

        $result = $this->getBlastEntities($result, $blast);

        return $result;
    }

    private function createAlignment($hsp, $blastTool)
    {
        /*
         * LEGEND
         */

        // Set the legend names
        $queryLegend = 'Query';
        $hitLegend = 'Hit';
        // Calculate the legend size
        $queryLegendLength = strlen($queryLegend);
        $hitLegendLength = strlen($hitLegend);
        // Then, which legend is the longer ?
        $maxLegendLength = max($queryLegendLength, $hitLegendLength);
        // What is the max digit length ?
        $maxDigitLength = strlen(max($hsp['query_from'], $hsp['query_to'], $hsp['hit_from'], $hsp['hit_to']));
        // Calculate the length of the longer legend (add 1 for the space between legend and number)
        $longerLegendLength = $maxLegendLength + 1 + $maxDigitLength;

        /*
         * The line converter function
         */
        $convertLine = function (int &$from, int $frame, int $step, &$line, $legend, int $legendLength, int $longerLegendLength) {
            // The line length, is the number of char - the number of gap (-)
            $lineLength = (strlen($line) - substr_count($line, '-')) * $step - 1;

            // Set $to, depending on the strand
            if ($frame >= 0) {
                $to = ($from + $lineLength);
            } else {
                $to = ($from - $lineLength);
            }

            // Calculate the number of spaces to add between the legend and the position
            $nbSpaces = $longerLegendLength - $legendLength - strlen($from);
            $lineLegend = $legend.str_repeat('&nbsp;', $nbSpaces);

            // Edit the line by adding the legend, and start/stop positions
            $line = $lineLegend.$from.' '.$line.' '.$to;

            // At the end, edit the from value, depending on the strand
            if ($frame >= 0) {
                $from = $to + 1;
            } else {
                $from = $to - 1;
            }
        };

        /*
         * Set some parameters depending on the blast tool
         */
        if ('tblastn' === $blastTool) { // for the tblastn
            $step['query'] = 1;
            $step['hit'] = 3;
        } elseif ('blastx' === $blastTool) { // for blastx
            $step['query'] = 3;
            $step['hit'] = 1;

            // Maybe a bug in blast, but the from and to, aren't inversed, we do it here
            if ($hsp['query_frame'] < 1) {
                $from = $hsp['query_from'];
                $to = $hsp['query_to'];
                $hsp['query_from'] = $to;
                $hsp['query_to'] = $from;
            }
        } elseif ('tblastx' === $blastTool) { //for tblastx
            $step['query'] = 3;
            $step['hit'] = 3;

            // Maybe a bug in blast, but the from and to, aren't inversed, we do it here
            if ($hsp['query_frame'] < 1) {
                $from = $hsp['query_from'];
                $to = $hsp['query_to'];
                $hsp['query_from'] = $to;
                $hsp['query_to'] = $from;
            }
        } else { // default parameters: for blastn and blastp
            $step['query'] = 1;
            $step['hit'] = 1;
        }

        /*
         * Convert the sequences
         */

        // Convert query sequences
        $from = $hsp['query_from'];
        foreach ($hsp['qseq'] as &$line) {
            $convertLine($from, $hsp['query_frame'], $step['query'], $line, $queryLegend, $queryLegendLength, $longerLegendLength);
        }

        // Convert midline
        foreach ($hsp['midline'] as &$line) {
            // Replace spaces by &nbsp; in the midline toavoid display bug when there is many spaces collapsed
            $line = str_replace(' ', '&nbsp;', $line);
            // In the midline, the number of spaces is
            // the length of the longer legend + 1 for the space between the legend and the sequence
            $line = str_repeat('&nbsp;', $longerLegendLength + 1).$line;
        }

        // Convert hit sequences
        $from = $hsp['hit_from'];
        foreach ($hsp['hseq'] as &$line) {
            $convertLine($from, $hsp['hit_frame'], $step['hit'], $line, $hitLegend, $hitLegendLength, $longerLegendLength);
        }

        /*
         * Return the $hsp
         */
        return $hsp;
    }

    private function getBlastEntities(array $blastResult, Blast $blast)
    {
        // If the user don't blast against CDS, return
        if ('chr' === $blast->getDatabase()) {
            return $blastResult;
        }

        $hits = [];
        foreach ($blastResult['iterations'] as $query) {
            foreach ($query['hits'] as $hit) {
                if (!in_array($hit['name'], $hits)) {
                    $hits[] = $hit['name'];
                }
            }
        }

        $entities = $this->em->getRepository('AppBundle:Locus')->findLocusFromProductWithoutDnaSequence($hits);
        $hits = array_combine($hits, $entities);

        foreach ($blastResult['iterations'] as &$query) {
            foreach ($query['hits'] as &$hit) {
                $hit['locus_entity'] = $hits[$hit['name']];
            }
        }

        return $blastResult;
    }
}
