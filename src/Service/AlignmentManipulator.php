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

namespace App\Service;

class AlignmentManipulator
{
    const SEQUENCE_LENGTH = 60;
    const LEGEND_INTERVAL = 10;
    const COLORATION_TYPES = ['identities', 'similarities', 'none'];
    const IDENTITIE_LEVELS = [1, 2, 3];

    private $sequenceManipulator;

    public function __construct(SequenceManipulator $sequenceManipulator)
    {
        $this->sequenceManipulator = $sequenceManipulator;
    }

    public function getAlignment($fasta)
    {
        // Transform fasta un an array of sequences
        $fastaArray = $this->sequenceManipulator->fastaToSequencesArray($fasta);

        // isNucleotidicFasta ?
        $isNucFasta = $this->sequenceManipulator->isNucleotidicFasta($fasta);

        // Explode the fastaArray in muliple line of self::SEQUENCE_LENGTH
        foreach ($fastaArray as &$sequence) {
            $sequence['sequence'] = str_split($sequence['sequence'], self::SEQUENCE_LENGTH);
            // Then explode each sequence line in unique base
            foreach ($sequence['sequence'] as &$sequenceLine) {
                $sequenceLine = str_split($sequenceLine, 1);
                // Do a subArray for each letter with 2 keys: base and style
                foreach ($sequenceLine as &$base) {
                    $base = [
                        'base' => $base,
                        'style' => null,
                    ];
                }
            }
        }

        // Define the number of row and sequences line in an alignment
        // It is the same for all sequences, do it for the first
        $nbRows = \count($fastaArray[0]['sequence']);
        $nbSequenceLines = \count($fastaArray);

        // Do the alignment
        $alignment = [
            'alignmentRows' => [],
            'isNucFasta' => $isNucFasta,
            'legendType' => null,
            'coloration' => [
                'type' => null,
            ],
        ];

        for ($i = 0; $i < $nbRows; ++$i) {
            $row = [];
            for ($j = 0; $j < $nbSequenceLines; ++$j) {
                $row[$j]['name'] = $fastaArray[$j]['name'];
                $row[$j]['sequence'] = $fastaArray[$j]['sequence'][$i];
            }
            $alignment['alignmentRows'][] = $row;
        }

        return $alignment;
    }

    public function getGlobalAlignment($fasta, $colorationType = null, int $identitiesLevel = null)
    {
        // First, do a standard alignment
        $alignment = $this->getAlignment($fasta);

        // The user want color the sequence ?
        // Color the sequence before add legend
        if ('none' !== $colorationType) {
            $alignment = $this->colorAlignment($alignment, $colorationType, $identitiesLevel);
        }

        // Generate the legend, then add it to the alignment
        // Because in an alignment all sequence lines have the same size, we just use the first in each row
        $nbRow = \count($alignment['alignmentRows']);
        for ($i = 0; $i < $nbRow; ++$i) {
            $sequenceLength = \count($alignment['alignmentRows'][$i][0]['sequence']);
            $legendPositions = ['name' => '', 'sequence' => ''];
            $legendFrame = ['name' => '', 'sequence' => []];
            $positions = [];

            // Create array legend
            for ($j = 1; $j <= $sequenceLength; ++$j) {
                // If it's the first
                if (1 === $j) {
                    $positions[] = $i * self::SEQUENCE_LENGTH + 1;
                    $legendFrame['sequence'][] = [
                        'base' => '|',
                        'style' => null,
                    ];
                }
                // Else if it's the last
                elseif ($j === $sequenceLength) {
                    $positions[] = $positions[0] + $sequenceLength - 1;
                    $legendFrame['sequence'][] = [
                        'base' => '|',
                        'style' => null,
                    ];
                }
                // Else if it's a multiple of self::LEGEND_INTERVAL
                elseif (0 === $j % self::LEGEND_INTERVAL) {
                    $positions[] = $positions[0] + $j - 1;
                    $legendFrame['sequence'][] = [
                        'base' => '+',
                        'style' => null,
                    ];
                }
                // Else, it's not a legend
                else {
                    $legendFrame['sequence'][] = [
                        'base' => '-',
                        'style' => null,
                    ];
                }
            }

            // Convert positions in text (permit use str_pad, to place legend correctly)
            $nbPositions = \count($positions);
            for ($j = 0; $j < $nbPositions; ++$j) {
                // If it's the first, do not add spaces before
                if (0 === $j) {
                    $padLength = 0;
                }
                // If it's the second, adapt nb spaces with the previous legend
                elseif (1 === $j) {
                    $padLength = self::LEGEND_INTERVAL - mb_strlen($positions[0]);
                }
                // If it's the last position, of the last row
                elseif ($j === ($nbPositions - 1) && $i === ($nbRow - 1)) {
                    $spaceBefore = $positions[$j] - $positions[$j - 1] - 1;
                    $padLength = $spaceBefore + mb_strlen($positions[$j]);
                }
                // Else, alway use the same space
                else {
                    $padLength = self::LEGEND_INTERVAL;
                }

                $legendPositions['sequence'] .= str_pad($positions[$j], $padLength, ' ', STR_PAD_LEFT);
            }

            // Split the legend position line in simple chars
            $legendPositions['sequence'] = str_split($legendPositions['sequence'], 1);

            // Then embed it in an array
            foreach ($legendPositions['sequence'] as &$base) {
                $base = [
                    'base' => $base,
                    'style' => null,
                ];
            }

            // Add legends to the alignment
            array_unshift($alignment['alignmentRows'][$i], $legendPositions, $legendFrame);
        }

        // Change the legendType to global
        $alignment['legendType'] = 'global';

        return $alignment;
    }

    private function colorAlignment($alignment, $colorationType = null, $identitiesColorationLevel = null)
    {
        // Define the number of sequences
        $nbSequences = \count($alignment['alignmentRows'][0]);

        // Define a default value for coloration
        if (!\in_array($colorationType, self::COLORATION_TYPES, true)) {
            if ($alignment['isNucFasta']) {
                $colorationType = 'identities';
            } else {
                $colorationType = 'similarities';
            }
        }

        // Verify the coloration type for nuc (similarities: not possible)
        if ($alignment['isNucFasta'] && 'similarities' === $colorationType) {
            $colorationType = 'identities';
        }

        // For an identities coloration
        if ('identities' === $colorationType) {
            // Do bases statistics
            // Make a statistic array (count bases)
            $basesStatistics = [];
            foreach ($alignment['alignmentRows'] as $key => $row) {
                $sequenceLength = \count($row[0]['sequence']);
                $nbSequences = \count($row);

                for ($i = 0; $i < $sequenceLength; ++$i) {
                    for ($j = 0; $j < $nbSequences; ++$j) {
                        $base = $row[$j]['sequence'][$i]['base'];

                        // Do not count '-'
                        if ('-' !== $base) {
                            if (isset($basesStatistics[$key][$i][$base])) {
                                ++$basesStatistics[$key][$i][$base];
                            } else {
                                $basesStatistics[$key][$i][$base] = 1;
                            }
                        }
                    }
                }
            }

            // Conservation coloration
            $identities100 = $nbSequences;
            $identities80 = floor($nbSequences * 0.8);
            $identities60 = floor($nbSequences * 0.6);

            // Set the $identitiesColorationLevel default value
            if (null === $identitiesColorationLevel || !\in_array($identitiesColorationLevel, self::IDENTITIE_LEVELS, true)) {
                $identitiesColorationLevel = 3;
            }

            // Define the conservation of each base
            foreach ($alignment['alignmentRows'] as $rowKey => &$row) {
                foreach ($row as &$alignmentLine) {
                    foreach ($alignmentLine['sequence'] as $baseKey => &$base) {
                        if ('-' !== $base['base']) {
                            $baseCount = $basesStatistics[$rowKey][$baseKey][$base['base']];

                            if ($baseCount === $identities100) {
                                $base['style'] = 'identities-100';
                            } elseif ($baseCount >= $identities80 && (2 === $identitiesColorationLevel || 3 === $identitiesColorationLevel)) {
                                $base['style'] = 'identities-80';
                            } elseif ($baseCount >= $identities60 && 3 === $identitiesColorationLevel) {
                                $base['style'] = 'identities-60';
                            }
                        }
                    }
                }
            }

            // Set coloration information
            $alignment['coloration']['type'] = $colorationType;
            $alignment['coloration']['identitiesLevel'] = $identitiesColorationLevel;
        }

        // For a smilarities coloration
        elseif ('similarities' === $colorationType) {
            // Properties coloration
            $styleTable = [
                'similarities-basic' => ['H', 'R', 'K'],
                'similarities-nonpolar' => ['F', 'A', 'L', 'M', 'I', 'W', 'P', 'V'],
                'similarities-polar' => ['C', 'G', 'Q', 'N', 'S', 'Y', 'T'],
                'similarities-acidic' => ['D', 'E'],
            ];

            foreach ($alignment['alignmentRows'] as &$row) {
                foreach ($row as &$alignmentLine) {
                    foreach ($alignmentLine['sequence'] as &$base) {
                        // Define the syle for each letter
                        if ('-' !== $base['base']) {
                            foreach ($styleTable as $style => $aas) {
                                if (\in_array($base['base'], $aas, true)) {
                                    $base['style'] = $style;

                                    break;
                                }
                            }
                        }
                    }
                }
            }

            // Set coloration information
            $alignment['coloration']['type'] = $colorationType;
        }

        return $alignment;
    }
}
