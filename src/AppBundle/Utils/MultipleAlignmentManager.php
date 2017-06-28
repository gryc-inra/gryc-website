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
    private $session;
    private $sequenceManipulator;

    public function __construct(EntityManager $em, Session $session, SequenceManipulator $sequenceManipulator)
    {
        $this->em = $em;
        $this->session = $session;
        $this->sequenceManipulator = $sequenceManipulator;
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

    public function fastaToArray($fasta, $colorationType = null)
    {
        $alignment = [];

        // Transform the fasta to an array
        $sequences = $this->sequenceManipulator->fastaToSequencesArray($fasta, "\n");
        $nbSequences = count($sequences);

        // Is a fasta of nucleotides or amino acids ?
        // To define it, count all letters, and do statistics (90% of atcg => nucleotides)
        // We define it on the first sequence, to avoid bug if user mix amino acids and nucleotides sequences
        // Get the number of a, t, c, g
        // The ascii code are:
        // 65 -> A, 97 -> a
        // 67 -> C, 99 -> c
        // 71 -> G, 103 -> g
        // 84 -> T, 116 -> t
        // 45 -> -
        $charsCount = count_chars($sequences[0]['sequence'], 1);
        // Remove '-' from the array
        unset($charsCount[45]);

        $nucCount = 0;
        $totalCount = 0;

        foreach ($charsCount as $i => $val) {
            // Add the number of chars to the totalCount
            $totalCount += $val;

            // If the letter is a a, t, c, g, add it to the nucCount
            if (in_array($i, [65, 97, 67, 99, 71, 103, 84, 116])) {
                $nucCount += $val;
            }
        }

        // Then decide, is it nuc or prot ? (if percentage of acgt is > to 90 % => nuc)
        if (($nucCount / $totalCount) > 0.9) {
            $alignment['sequence_type'] = 'nuc';
        } else {
            $alignment['sequence_type'] = 'prot';
        }

        // Add all sequences compared at each row in alignment array
        $i = 0;
        foreach ($sequences as $key => $value) {
            // Define the sequence name and an array of bases (60 per line)
            $sequenceName = strlen($value['name']) > 20 ? substr($value['name'], 0, 17).'...' : $value['name'];
            $basesLines = str_split($value['sequence'], 60);

            // Create the table
            $basesLinesCount = count($basesLines) - 1;
            $end = 0;
            $longerPosition = strlen($basesLinesCount * 60);
            for ($j = 0; $j <= $basesLinesCount; ++$j) {
                $basesLength = strlen($basesLines[$j]);

                // Define positions
                $start = $end + 1;
                $end = $start + $basesLength - 1;

                // Define name and bases (add spaces, to align text)
                $line['name'] = str_pad($sequenceName, 20, ' ', STR_PAD_RIGHT);
                $line['bases'] = str_split($basesLines[$j]);
                $line['start'] = str_pad($start, $longerPosition, ' ', STR_PAD_LEFT);
                $line['end'] = (string) $end;

                // Assign to the array
                $alignment['rows'][$j]['alignment_rows'][$i] = $line;

                if (!isset($alignment['rows'][$j]['length'])) { // Define the length only one time
                    $alignment['rows'][$j]['length'] = $basesLength;
                }
            }

            ++$i;
        }

        // Count the bases, and add an array of it in the alignment array
        foreach ($alignment['rows'] as $key => $value) {
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
            $alignment['rows'][$key]['bases_count'] = $basesCount;
        }

        // Color the sequence
        // Possibilities (*=> default):
        // nuc: none, *conservation*
        // prot: none, conservation, *properties*

        // Define a default value
        if ('identities' !== $colorationType && 'similarities' !== $colorationType && 'none' !== $colorationType) {
            if ('nuc' === $alignment['sequence_type']) {
                $colorationType = 'identities';
            } else {
                $colorationType = 'similarities';
            }
        }

        // Verify the coloration type for nuc (similarities: not possible)
        if ('nuc' === $alignment['sequence_type'] && 'similarities' === $colorationType) {
            $colorationType = 'identities';
        }

        $alignment['coloration'] = $colorationType;

        // Color with the appropriate method
        if ('identities' === $colorationType) {
            // Conservation coloration
            $identical100 = $nbSequences;
            $identical80 = floor($nbSequences * 0.8);
            $identical60 = floor($nbSequences * 0.6);

            foreach ($alignment['rows'] as &$row) {
                foreach ($row['alignment_rows'] as &$alignmentRow) {
                    $i = 0;
                    foreach ($alignmentRow['bases'] as &$base) {
                        $style = null;

                        if ('-' !== $base) {
                            $count = $row['bases_count'][$i][$base];

                            if ($count == $identical100) {
                                $style = 'identities-100';
                            } elseif ($count >= $identical80) {
                                $style = 'identities-80';
                            } elseif ($count >= $identical60) {
                                $style = 'identities-60';
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
        } elseif ('similarities' === $colorationType) {
            // Properties coloration
            $classTable = [
                'similarities-basic' => ['H', 'R', 'K'],
                'similarities-nonpolar' => ['F', 'A', 'L', 'M', 'I', 'W', 'P', 'V'],
                'similarities-polar' => ['C', 'G', 'Q', 'N', 'S', 'Y', 'T'],
                'similarities-acidic' => ['D', 'E'],
            ];

            foreach ($alignment['rows'] as &$row) {
                foreach ($row['alignment_rows'] as &$alignmentRow) {
                    $i = 0;
                    foreach ($alignmentRow['bases'] as &$base) {
                        // Define the syle for each letter
                        $style = null;

                        if ('-' !== $base) {
                            foreach ($classTable as $class => $aas) {
                                if (in_array($base, $aas)) {
                                    $style = $class;
                                    break;
                                }
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
        } else {
            // No coloration
            foreach ($alignment['rows'] as &$row) {
                foreach ($row['alignment_rows'] as &$alignmentRow) {
                    $i = 0;
                    foreach ($alignmentRow['bases'] as &$base) {
                        $base = [
                            'letter' => $base,
                            'style' => null,
                        ];

                        ++$i;
                    }
                }
            }
        }

        return $alignment;
    }
}
