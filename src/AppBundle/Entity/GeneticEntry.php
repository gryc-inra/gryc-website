<?php

namespace AppBundle\Entity;

use AppBundle\Utils\SequenceManipulator;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * GeneticEntry.
 *
 * @ORM\MappedSuperclass
 */
class GeneticEntry
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

    public function getSequence($upstream = 0, $downstream = 0)
    {
        $class = explode('\\', get_class($this))[2];
        switch ($class) {
            case 'Locus':
                $chromosomeDna = $this->getChromosome()->getDnaSequence()->getDna();
                $locusStart = $this->start - 1;
                $locusEnd = $this->end - 1;
                break;
            case 'Feature':
                $chromosomeDna = $this->getLocus()->getChromosome()->getDnaSequence()->getDna();
                $locusStart = $this->getLocus()->getStart() - 1;
                $locusEnd = $this->getLocus()->getEnd() - 1;
                break;
            case 'Product':
                $chromosomeDna = $this->getFeature()->getLocus()->getChromosome()->getDnaSequence()->getDna();
                $locusStart = $this->getFeature()->getLocus()->getStart() - 1;
                $locusEnd = $this->getFeature()->getLocus()->getEnd() - 1;
                break;
        }
        // Verify the overStart and overEnd don't go out the sequence
        if (($locusStart - $upstream) < 0) {
            $locusStart = 0;
        } else {
            $locusStart -= $upstream;
        }

        if (($locusEnd + $downstream) > strlen($chromosomeDna)) {
            $locusEnd = strlen($chromosomeDna);
        } else {
            $locusEnd += $downstream;
        }

        $sequenceLength = $locusEnd - $locusStart + 1;
        $sequence = substr($chromosomeDna, $locusStart, $sequenceLength);

        if (-1 === $this->strand) {
            $sequenceManipulator = new SequenceManipulator();
            $sequence = $sequenceManipulator->reverseComplement($sequence);

            $this->coordinates = array_reverse($this->coordinates);
        }

        // Get Exons and UTR
        // Exons
        $exonCount = 0;
        $exonSpanStart = '<span class="exon">';
        $exonSpanEnd = '</span>';
        $exonSpanStartSize = strlen($exonSpanStart);
        $exonSpanEndSize = strlen($exonSpanEnd);

        // UTR
        $utrCount = 0;
        $utrSpanStart = '<span class="utr">';
        $utrSpanEnd = '</span>';
        $utrSpanStartSize = strlen($utrSpanStart);
        $utrSpanEndSize = strlen($utrSpanEnd);

        // For each coordinate
        foreach ($this->coordinates as $coordinate) {
            $coord = explode('..', $coordinate);

            if (1 === $this->strand) {
                $start = ($coord[0] - 1) - $locusStart;
                $end = (($coord[1] - 1) - $locusStart) + 1;
            } else {
                $start = $locusEnd - ($coord[1] - 1);
                $end = ($locusEnd - ($coord[0] - 1)) + 1;
            }

            // Add Exon
            $sequence = substr_replace($sequence, $exonSpanStart, $start + (($exonSpanStartSize + $exonSpanEndSize) * $exonCount + ($utrSpanStartSize + $utrSpanEndSize) * $utrCount), 0);
            $sequence = substr_replace($sequence, $exonSpanEnd, $end + ($exonSpanStartSize * ($exonCount + 1)) + ($exonSpanEndSize * $exonCount) + ($utrSpanStartSize + $utrSpanEndSize) * $utrCount, 0);

            // Add UTR
            if (0 === $exonCount && 0 !== $start ) {
                $sequence = substr_replace($sequence, $utrSpanStart, 0, 0);
                $sequence = substr_replace($sequence, $utrSpanEnd, $start + $utrSpanStartSize, 0);
                $utrCount++;
            }
            if (count($this->coordinates) == ($exonCount + 1)) {
                $sequence = substr_replace($sequence, $utrSpanStart, $end + (($exonSpanStartSize + $exonSpanEndSize) * ($exonCount + 1)) + (($utrSpanStartSize + $utrSpanEndSize) * $utrCount), 0);
                $sequence = substr_replace($sequence, $utrSpanEnd, strlen($sequence), 0);
                $utrCount++;
            }

            $exonCount++;
        }

        // Cut the sequence in array with 60 nucleotides per line
        $letters = str_split($sequence, 1);
        $sequence = [];
        $sequence[0] = '';
        $i = 0;
        $l = 0;
        foreach($letters as $letter) {
            $type = ctype_upper($letter);

            if (!$type) {
                $sequence[$l] .= $letter;
            } elseif ($type && $i < 60) {
                $i++;
                $sequence[$l] .= $letter;
            } else {
                $l++;
                $i = 1;
                $sequence[$l] = $letter;
            }
        }

        return $sequence;
    }
}
