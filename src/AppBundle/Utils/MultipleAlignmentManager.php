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

    public function fastaToArray($fasta)
    {
        // First, separate sequences in a sequences array
        $sequences = explode('>', $fasta);
        // We cut on >, then the first line is empty, remove it
        unset($sequences[0]);
        $sequences = array_values($sequences);
        $nbSequences = count($sequences);

        $alignment = [];
        $i = 0;
        foreach ($sequences as $key => $value) {
            // Explode the sequence on newline char, then define the sequence as an array
            $explodedSequence = explode("\n", $value);
            $sequenceName = array_shift($explodedSequence);
            $sequenceName = strlen($sequenceName) > 20 ? substr($sequenceName, 0, 17).'...' : $sequenceName;
            $basesLines = array_slice($explodedSequence, 0, -1);

            // Create the table
            $basesLinesCount = count($basesLines) - 1;
            $end = 0;
            $longerPosition = strlen(count($explodedSequence) * 60);
            for ($j = 0; $j <= $basesLinesCount; ++$j) {
                $basesLength = strlen($basesLines[$j]);

                // Define positions
                $start = $end + 1;
                $end = $start + $basesLength - 1;

                // Define name and bases
                $line['name'] = str_pad($sequenceName, 20, ' ', STR_PAD_RIGHT);
                $line['bases'] = str_split($basesLines[$j]);
                $line['start'] = str_pad($start, $longerPosition, ' ', STR_PAD_LEFT);
                $line['end'] = $end;

                // Assign to the array
                $alignment[$j]['alignment_rows'][$i] = $line;

                if (!isset($alignment[$j]['length'])) { // Define the length only one time
                    $alignment[$j]['length'] = $basesLength;
                }
            }

            ++$i;
        }

        foreach ($alignment as $key => $value) {
            $sequenceLength = $value['length'] - 1;
            $sequenceNumber = count($value['alignment_rows']) - 1;
            $basesCount = [];

            // Check all bases for each position
            // We count the letters, and add it in a table
            for ($i = 0; $i <= $sequenceLength; ++$i) {
                for ($j = 0; $j <= $sequenceNumber; ++$j) {
                    $base = $value['alignment_rows'][$j]['bases'][$i];

                    // Do not count '-'
                    if ('-' !== $base) {
                        if (isset($basesCount[$i][$base])) {
                            $basesCount[$i][$base] += 1;
                        } else {
                            $basesCount[$i][$base] = 1;
                        }
                    }
                }
            }

            // Add the basesCount table as "bases_count"
            $alignment[$key]['bases_count'] = $basesCount;
        }

        // Define color % identical
        $identical100 = $nbSequences;
        $identical80 = floor($nbSequences * 0.8);
        $identical60 = floor($nbSequences * 0.6);

        foreach ($alignment as &$row) {
            foreach ($row['alignment_rows'] as &$alignmentRow) {
                $i = 0;
                foreach ($alignmentRow['bases'] as &$base) {
                    // Define the syle for each letter
                    $style = null;

                    if ('-' !== $base) {
                        $count = $row['bases_count'][$i][$base];

                        if ($count == $identical100) {
                            $style = 'identical-100';
                        } elseif ($count >= $identical80) {
                            $style = 'identical-80';
                        } elseif ($count >= $identical60) {
                            $style = 'identical-60';
                        }
                    }

                    $base = [
                        'letter' => $base,
                        'style' => $style,
                    ];

                    ++$i;
                }
            }
        }

        return $alignment;
    }
}
