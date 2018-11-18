<?php

/*
 * Copyright 2015-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Service;

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

    private function nucleotideFasta(array $locusList, string $featureType, bool $intronSplicing, int $upstream, int $downstream)
    {
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
            if ('locus' === $featureType) {
                $fastaData[] = $locus->getSequence(true, $upstream, $downstream, false);
            }
            // Else, do a while on Features
            else {
                foreach ($locus->getFeatures() as $feature) {
                    // If the user want feature, compute it
                    if ('feature' === $featureType) {
                        $fastaData[] = $feature->getSequence(!$intronSplicing, $upstream, $downstream, false);
                    }
                    // Else, do a while on Products and compute it
                    else {
                        foreach ($feature->getProductsFeatures() as $product) {
                            $fastaData[] = $product->getSequence(!$intronSplicing, $upstream, $downstream, false);
                        }
                    }
                }
            }
        }

        $fasta = implode("\n\n", $fastaData);

        return $fasta;
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
        $handle = fopen('php://output', 'w+b');
        fwrite($handle, $fasta);
        fclose($handle);

        return $handle;
    }
}
