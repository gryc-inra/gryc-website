<?php

namespace AppBundle\Utils;

class FastaGenerator
{
    const FASTA_LINE_LENGTH = 60;

    public function generateFasta(array $formData, array $locusList)
    {
        if ('prot' === $formData['type']) {
            $this->aminoAcidsFasta($locusList);
        } else {
            // The user want nucleotides
            $this->nucleotideFasta($formData, $locusList);
        }
    }

    private function nucleotideFasta($formData, $locusList)
    {
        // if feature is locus do not use intronSplicing
        // Per security, we set it to false here
        if ('locus' === $formData['feature']) {
            $formData['intronSplicing'] = false;
        }

        // if the intron splicing is true, remove upstream and downstream
        if (true === $formData['intronSplicing']) {
            $formData['upstream'] = 0;
            $formData['downstream'] = 0;
        }

        // Then generate a fastaData array
        $fastaData = [];

        // While on Locus
        foreach ($locusList as $locus) {
            // If the user want locus, compute it
            if ('locus' === $formData['feature']) {
                $fastaData[] = [
                    'name' => $locus->getName(),
                    'sequence' => $locus->getSequence(true, true, $formData['upstream'], $formData['downstream'], false),
                ];
            // Else, do a while on Features
            } else {
                foreach ($locus->getFeatures() as $feature) {
                    // If the user want feature, compute it
                    if ('feature' === $formData['feature']) {
                        $fastaData[] = [
                            'name' => $feature->getName(),
                            'sequence' => $feature->getSequence(false, !$formData['intronSplicing'], $formData['upstream'], $formData['downstream'], false),
                        ];
                    // Else, do a while on Products and compute it
                    } else {
                        foreach ($feature->getProductsFeatures() as $product) {
                            $fastaData[] = [
                                'name' => $product->getName(),
                                'sequence' => $product->getSequence(false, !$formData['intronSplicing'], $formData['upstream'], $formData['downstream'], false),
                            ];
                        }
                    }
                }
            }
        }

        $this->arrayToFasta($fastaData);
    }

    private function aminoAcidsFasta($locusList)
    {
        $fastaData = [];

        foreach ($locusList as $locus) {
            foreach ($locus->getFeatures() as $feature) {
                foreach ($feature->getProductsFeatures() as $product) {
                    $sequence = str_split($product->getTranslation(), self::FASTA_LINE_LENGTH);

                    $fastaData[] = [
                        'name' => $product->getName(),
                        'sequence' => $sequence,
                    ];
                }
            }
        }

        $this->arrayToFasta($fastaData);
    }

    private function arrayToFasta($fastaData)
    {
        $fasta = '';

        foreach ($fastaData as $key => $value) {
            if ($key > 0) {
                $fasta .= "\n";
            }

            $fasta .= '>'.$value['name']."\n";
            foreach ($value['sequence'] as $line) {
                $fasta .= $line."\n";
            }
        }

        $handle = fopen('php://output', 'w+');
        fwrite($handle, $fasta);
        fclose($handle);

        return $handle;
    }

    public function fastaToArray($fasta)
    {
        // First, separate sequences in a sequences array
        $sequences = explode('>', $fasta);
        // We cut on >, then the first line is empty, remove it
        unset($sequences[0]);
        $sequences = array_values($sequences);

        $basesTable = [];
        $i = 0;
        foreach ($sequences as $sequence) {
            // Explode the sequence on newline char, then define the sequence as an array
            $explodedSequence = explode("\n", $sequence);
            $sequenceName = array_shift($explodedSequence);
            $sequenceName = strlen($sequenceName) > 20 ? substr($sequenceName, 0, 17).'...' : $sequenceName;
            $basesLines = array_slice($explodedSequence, 0, -1);

            // Create the table
            $basesLinesCount = count($basesLines) - 1;
            $end = 0;
            $longerPosition = strlen(count($explodedSequence) * 60);
            for ($j = 0; $j <= $basesLinesCount; ++$j) {
                // Define positions
                $start = $end + 1;
                $end = $start + strlen($basesLines[$j]) - 1;

                // Because we start from the end, the first position is the longer
                if ($basesLinesCount === $j) {
                    $longerPosition = strlen($end);
                }

                // Define name and bases
                $line['name'] = str_pad($sequenceName, 20, ' ', STR_PAD_RIGHT);
                $line['bases'] = str_split($basesLines[$j]);
                $line['start'] = str_pad($start, $longerPosition, ' ', STR_PAD_LEFT);
                $line['stop'] = $end;

                $basesTable[$j][$i] = $line;
            }

            ++$i;
        }

        return $basesTable;
    }
}
