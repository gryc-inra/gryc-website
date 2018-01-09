<?php

namespace AppBundle\Service;

class FastaGenerator
{
    const FASTA_LINE_LENGTH = 60;

    private $stream = false;

    public function __construct(bool $stream = false)
    {
        $this->stream = $stream;
    }

    public function generateFasta(array $locusList, string $type = 'nuc', string $feature = 'locus', bool $intronSplicing = false, int $upstream = 0, int $downstream = 0)
    {
        if ('prot' === $type) {
            $fasta = $this->aminoAcidsFasta($locusList);
        } else {
            // The user want nucleotides
            $fasta = $this->nucleotideFasta($locusList, $feature, $intronSplicing, $upstream, $downstream);
        }

        if ($this->stream) {
            return $this->streamFasta($fasta);
        }

        return $fasta;
    }

    private function nucleotideFasta(array $locusList, string $feature, bool $intronSplicing, int $upstream, int $downstream)
    {
        // if feature is locus do not use intronSplicing
        // Per security, we set it to false here
        if ('locus' === $feature) {
            $intronSplicing = false;
        }

        // if the intron splicing is true, remove upstream and downstream
        if (true === $intronSplicing) {
            $upstream = 0;
            $downstream = 0;
        }

        // Then generate a fastaData array
        $fastaData = [];

        // While on Locus
        foreach ($locusList as $locus) {
            // If the user want locus, compute it
            if ('locus' === $feature) {
                $fastaData[] = [
                    'name' => $locus->getName(),
                    'sequence' => $locus->getSequence(true, true, $upstream, $downstream, false),
                ];
            }
            // Else, do a while on Features
            else {
                foreach ($locus->getFeatures() as $feature) {
                    // If the user want feature, compute it
                    if ('feature' === $feature) {
                        $fastaData[] = [
                            'name' => $feature->getName(),
                            'sequence' => $feature->getSequence(false, !$intronSplicing, $upstream, $downstream, false),
                        ];
                    }
                    // Else, do a while on Products and compute it
                    else {
                        foreach ($feature->getProductsFeatures() as $product) {
                            $fastaData[] = [
                                'name' => $product->getName(),
                                'sequence' => $product->getSequence(false, !$intronSplicing, $upstream, $downstream, false),
                            ];
                        }
                    }
                }
            }
        }

        return $this->arrayToFasta($fastaData);
    }

    private function aminoAcidsFasta(array $locusList)
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

        return $this->arrayToFasta($fastaData);
    }

    private function arrayToFasta(array $fastaData)
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

        return $fasta;
    }

    private function streamFasta(string $fasta)
    {
        $handle = fopen('php://output', 'w+');
        fwrite($handle, $fasta);
        fclose($handle);

        return $handle;
    }
}
