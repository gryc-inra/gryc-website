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

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * GeneticEntry.
 *
 * @ORM\MappedSuperclass
 */
abstract class GeneticEntry
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="strand", type="smallint")
     */
    private $strand;

    /**
     * @var array
     *
     * @ORM\Column(name="product", type="array")
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * A slug, for url.
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="dbXref", type="object", nullable=true)
     */
    private $dbXref;

    /**
     * @var array
     *
     * @ORM\Column(name="annotation", type="array")
     */
    private $annotation;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var array
     *
     * @ORM\Column(name="coordinates", type="array")
     */
    private $coordinates;

    /**
     * @var int
     *
     * @ORM\Column(name="start", type="integer")
     */
    private $start;

    /**
     * @var int
     *
     * @ORM\Column(name="end", type="integer")
     */
    private $end;

    /**
     * @var string
     *
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

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set strand.
     *
     * @param int $strand
     *
     * @return GeneticEntry
     */
    public function setStrand($strand)
    {
        $this->strand = $strand;

        return $this;
    }

    /**
     * Get strand.
     *
     * @return int
     */
    public function getStrand()
    {
        return $this->strand;
    }

    /**
     * Set product.
     *
     * @param array $product
     *
     * @return GeneticEntry
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return array
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return GeneticEntry
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set dbXref.
     *
     * @param \stdClass $dbXref
     *
     * @return GeneticEntry
     */
    public function setDbXref($dbXref)
    {
        $this->dbXref = $dbXref;

        return $this;
    }

    /**
     * Get dbXref.
     *
     * @return \stdClass
     */
    public function getDbXref()
    {
        return $this->dbXref;
    }

    /**
     * Set annotation.
     *
     * @param array $annotation
     *
     * @return GeneticEntry
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }

    /**
     * Get annotation.
     *
     * @return array
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return GeneticEntry
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set coordinates.
     *
     * @param array $coordinates
     *
     * @return GeneticEntry
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * Get coordinates.
     *
     * @return array
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set start.
     *
     * @param int $start
     *
     * @return GeneticEntry
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start.
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end.
     *
     * @param int $end
     *
     * @return GeneticEntry
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end.
     *
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set note.
     *
     * @param string $note
     *
     * @return GeneticEntry
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    public function getSequence($showIntronUtr = true, $upstream = 0, $downstream = 0, $html = true)
    {
        if ($this instanceof Locus) {
            $locusSequence = $this->getLocusSequence();
            $upstreamSequence = $this->getUpstreamSequence();
            $downstreamSequence = $this->getDownstreamSequence();
            $structure = [['type' => 'o', 'start' => 0, 'end' => mb_strlen($this->getLocusSequence())]];
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
            $sequences[$key]['seq'] = mb_substr($locusSequence, $value['start'], $value['end'] - $value['start']);
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
        $nbLetters = count($letters);
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
