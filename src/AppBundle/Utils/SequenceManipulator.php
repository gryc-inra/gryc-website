<?php

namespace AppBundle\Utils;

class SequenceManipulator
{
    public function reverse($sequence)
    {
        return strrev($sequence);
    }

    public function complement($sequence)
    {
        $complementaryTable = [
            'A' => 'T',
            'T' => 'A',
            'U' => 'A',
            'G' => 'C',
            'C' => 'G',
            'Y' => 'R',
            'R' => 'Y',
            'S' => 'S',
            'W' => 'W',
            'K' => 'M',
            'M' => 'K',
            'B' => 'V',
            'D' => 'H',
            'H' => 'D',
            'V' => 'B',
            'N' => 'N',
        ];

        $bases = str_split($sequence, 1);
        foreach ($bases as &$base) {
            $base = $complementaryTable[strtoupper($base)];
        }

        return implode('', $bases);
    }

    public function reverseComplement($sequence)
    {
        return $this->complement($this->reverse($sequence));
    }

    public function fastaToSequencesArray($fasta, $delimiter = "\r\n")
    {
        // First, separate sequences in a sequences array
        $sequences = explode('>', $fasta);
        // We cut on >, then the first line is empty, remove it
        unset($sequences[0]);
        $sequences = array_values($sequences);

        for ($i = 0; $i <= count($sequences) - 1; ++$i) {
            // Explode the sequence on newline char, then define the sequence as an array
            $explodedSequence = explode($delimiter, $sequences[$i]);
            unset($sequences[$i]);
            $sequences[$i]['name'] = array_shift($explodedSequence);
            $sequences[$i]['sequence'] = implode($explodedSequence);
        }

        return $sequences;
    }

    public function arrayToFasta($sequencesArray, $delimiter = "\r\n") {
        $i = 0;
        $fasta = '';
        foreach ($sequencesArray as $sequence) {
            if ($i != 0) {
                $fasta .= "\n\n";
            }

            $fasta .= '>'.$sequence['name']."\n";

            $sequence60 = str_split($sequence['sequence'], 60);
            foreach ($sequence60 as $seq60) {
                $fasta .= $seq60;
            }

            ++$i;
        }

        return $fasta;
    }

    public function processManipulation($fasta, $action)
    {
        // Transform the fasta in an array
        $sequences = $this->fastaToSequencesArray($fasta);

        // For each sequence proceed to the selected action
        foreach ($sequences as &$sequence) {
            switch ($action) {
                case 'reverse-complement':
                    $sequence['name'] = $sequence['name'].'.rev-comp';
                    $sequence['sequence'] = $this->reverseComplement($sequence['sequence']);

                    break;

                case 'reverse':
                    $sequence['name'] = $sequence['name'].'.rev';
                    $sequence['sequence'] = $this->reverse($sequence['sequence']);

                    break;

                case 'complement':
                    $sequence['name'] = $sequence['name'].'.comp';
                    $sequence['sequence'] = $this->complement($sequence['sequence']);

                    break;
            }
        }

        return $this->arrayToFasta($sequences);
    }
}
