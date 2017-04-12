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
            $base = $complementaryTable[$base];
        }

        return implode('', $bases);
    }

    public function reverseComplement($sequence)
    {
        return $this->complement($this->reverse($sequence));
    }
}
