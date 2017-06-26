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
}
