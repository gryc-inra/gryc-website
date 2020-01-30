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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\MappedSuperclass
 */
abstract class GeneticEntry
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="strand", type="smallint")
     */
    private $strand;

    /**
     * @ORM\Column(name="product", type="array")
     */
    private $product;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="dbXref", type="object", nullable=true)
     */
    private $dbXref;

    /**
     * @ORM\Column(name="annotation", type="array")
     */
    private $annotation;

    /**
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(name="coordinates", type="array")
     */
    private $coordinates;

    /**
     * @ORM\Column(name="start", type="integer")
     */
    private $start;

    /**
     * @ORM\Column(name="end", type="integer")
     */
    private $end;

    /**
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    const STRUCTURE_TYPES = [
        'l' => 'locus',
        'f' => 'feature',
        'p' => 'product',
        'i' => 'intron',
        'r' => 'repeats',
        'o' => 'other',
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setStrand(int $strand): self
    {
        $this->strand = $strand;

        return $this;
    }

    public function getStrand(): ?int
    {
        return $this->strand;
    }

    public function setProduct(array $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): array
    {
        return $this->product;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setDbXref(Dbxref $dbXref): self
    {
        $this->dbXref = $dbXref;

        return $this;
    }

    public function getDbXref(): Dbxref
    {
        return $this->dbXref;
    }

    public function setAnnotation(array $annotation): self
    {
        $this->annotation = $annotation;

        return $this;
    }

    public function getAnnotation(): array
    {
        return $this->annotation;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setCoordinates(array $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    public function setStart(int $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function setEnd(int $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getEnd(): int
    {
        return $this->end;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function getSequence($showIntronUtr = true, $upstream = 0, $downstream = 0, $html = true): ?string
    {
        if ($this instanceof Locus) {
            $locusSequence = $this->getLocusSequence();
            $upstreamSequence = $this->getUpstreamSequence();
            $downstreamSequence = $this->getDownstreamSequence();
            $structure = [['type' => 'o', 'start' => 0, 'end' => mb_strlen($this->getLocusSequence()) - 1]];
        } elseif ($this instanceof Feature) {
            $locusSequence = $this->getLocus()->getLocusSequence();
            $upstreamSequence = $this->getLocus()->getUpstreamSequence();
            $downstreamSequence = $this->getLocus()->getDownstreamSequence();
            $structure = $this->getStructure();
        } elseif ($this instanceof Product) {
            $locusSequence = $this->getFeature()->getLocus()->getLocusSequence();
            $upstreamSequence = $this->getFeature()->getLocus()->getUpstreamSequence();
            $downstreamSequence = $this->getFeature()->getLocus()->getDownstreamSequence();
            $structure = $this->getStructure();
        }

        // If the structure is null, return
        if (null === $structure) {
            return null;
        }

        // Retrieve sequence parts
        foreach ($structure as $key => $value) {
            // If user don't want show UTR or/and intron and the type is intron or UTR, skip the loop
            if ((!$showIntronUtr && 'i' === $value['type']) || (!$showIntronUtr && 'f' === $value['type'])) {
                continue;
            }

            $sequences[$key]['type'] = Locus::STRUCTURE_TYPES[$value['type']];
            $sequences[$key]['seq'] = mb_substr($locusSequence, $value['start'], $value['end'] - $value['start'] + 1);
        }

        // Add upstream and downstream parts
        if ($upstream > 0) {
            array_unshift($sequences, [
                'type' => 'stream',
                'seq' => mb_substr($upstreamSequence, -0, $upstream),
            ]);
        }

        if ($downstream > 0) {
            array_push($sequences, [
                'type' => 'stream',
                'seq' => mb_substr($downstreamSequence, 0, $downstream),
            ]);
        }

        // Stick sequence parts to obtain the full sequence
        $sequence = '';
        foreach ($sequences as $sequencePart) {
            if ($html) {
                $sequence .= '<span class="'.$sequencePart['type'].'">'.$sequencePart['seq'].'</span>';
            } else {
                $sequence .= $sequencePart['seq'];
            }
        }

        // Transform to a FASTA format
        $letters = str_split($sequence, 1);
        $nbLetters = \count($letters);
        $currentLineLength = 0;
        $sequence = '>'.$this->getName()."\n";
        for ($i = 0; $i < $nbLetters; ++$i) {
            $isUpper = ctype_upper($letters[$i]);

            if (false === $isUpper) {
                $sequence .= $letters[$i];
            } elseif (true === $isUpper && $currentLineLength < 60) {
                ++$currentLineLength;
                $sequence .= $letters[$i];
            } else {
                $currentLineLength = 1;
                $sequence .= "\n";
                $sequence .= $letters[$i];
            }
        }

        // Return the sequence
        return $sequence;
    }
}
