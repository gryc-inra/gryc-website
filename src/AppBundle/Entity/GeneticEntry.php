<?php

namespace AppBundle\Entity;

use AppBundle\Utils\SequenceManipulator;
use Doctrine\ORM\Mapping as ORM;

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

    public function getSequence($showUtr = true, $showIntron = true, $upstream = 0, $downstream = 0, $html = true)
    {
        $class = explode('\\', get_class($this))[2];
        switch ($class) {
            case 'Locus':
                $chromosomeDna = $this->getChromosome()->getDnaSequence()->getDna();
                $locusStart = $this->start;
                $locusEnd = $this->end;
                break;
            case 'Feature':
                $chromosomeDna = $this->getLocus()->getChromosome()->getDnaSequence()->getDna();
                $locusStart = $this->getLocus()->getStart();
                $locusEnd = $this->getLocus()->getEnd();
                break;
            case 'Product':
                $chromosomeDna = $this->getFeature()->getLocus()->getChromosome()->getDnaSequence()->getDna();
                $locusStart = $this->getFeature()->getLocus()->getStart();
                $locusEnd = $this->getFeature()->getLocus()->getEnd();
                break;
        }

        // Create a positionsArray
        $positionsArray = [];

        // First, define the upstream
        if (($upstream > 0 && 1 === $this->strand) || ($downstream > 0 && 1 !== $this->strand)) {
            $stream = (1 === $this->strand) ? $upstream : $downstream;

            if (($locusStart - $stream) < 0) {
                $positionsArray['upstream']['start'] = 1;
            } else {
                $positionsArray['upstream']['start'] = $locusStart - $stream;
            }
            $positionsArray['upstream']['end'] = $locusStart - 1;
            $positionsArray['upstream']['legend'] = 'stream';
        } else {
            $positionsArray['upstream'] = false;
        }

        // Is there 5'UTR region ?
        $firstExonCoord = explode('..', $this->coordinates[0]);
        // If the first exon start position is greater than the locus start position, there is a 5'UTR
        if (true === $showUtr && $firstExonCoord[0] > $locusStart) {
            $positionsArray['5UTR']['start'] = $locusStart;
            $positionsArray['5UTR']['end'] = $firstExonCoord[0] - 1;
            $positionsArray['5UTR']['legend'] = 'utr';
        } else {
            $positionsArray['5UTR'] = false;
        }

        // Do a while on coordinates to get all exon and determine introns
        $i = 0;
        $nbExons = count($this->coordinates);
        $nbIntrons = $nbExons - 1;
        $haveIntron = $nbIntrons > 0 ? true : false;
        foreach ($this->coordinates as $coordinate) {
            $coord = explode('..', $coordinate);

            // If the strain have intron, had them
            // Intron only between the 2nd and before last loop
            if (true === $showIntron && $haveIntron && $i > 0 && $i < $nbExons) {
                $positionsArray['intron-'.($i - 1)]['start'] = $positionsArray['exon-'.($i - 1)]['end'] + 1;
                $positionsArray['intron-'.($i - 1)]['end'] = (int) $coord[0] - 1;
                $positionsArray['intron-'.($i - 1)]['legend'] = 'intron';
            }

            // Set the exon
            $positionsArray['exon-'.$i]['start'] = (int) $coord[0];
            $positionsArray['exon-'.$i]['end'] = (int) $coord[1];
            $positionsArray['exon-'.$i]['legend'] = 'exon';

            ++$i;
        }

        // Is there 3'UTR region ?
        $lastExonCoord = explode('..', $this->coordinates[$nbExons - 1]);
        // If the last exon end position is smaller than the locus end position, there is a 3'UTR
        if (true === $showUtr && $lastExonCoord[1] < $locusEnd) {
            $positionsArray['3UTR']['start'] = $lastExonCoord[1] + 1;
            $positionsArray['3UTR']['end'] = $locusEnd;
            $positionsArray['3UTR']['legend'] = 'utr';
        } else {
            $positionsArray['3UTR'] = false;
        }

        // Then, define the downstream
        if (($downstream > 0 && 1 === $this->strand) || ($upstream > 0 && 1 !== $this->strand)) {
            $stream = (1 === $this->strand) ? $downstream : $upstream;

            $positionsArray['downstream']['start'] = $locusEnd + 1;
            $positionsArray['downstream']['legend'] = 'stream';

            if (($locusEnd + $stream) > strlen($chromosomeDna)) {
                $positionsArray['downstream']['end'] = strlen($chromosomeDna);
            } else {
                $positionsArray['downstream']['end'] = $locusEnd + $stream;
            }
        } else {
            $positionsArray['downstream'] = false;
        }

        // Convert positions from human logic to computer logic
        array_walk_recursive($positionsArray, function (&$item) {
            --$item;
        });

        dump($positionsArray);
        if (1 !== $this->strand) {
            $positionsArray = array_reverse($positionsArray);
            $sequenceManipulator = new SequenceManipulator();
        }
        dump($positionsArray);

        $sequences = [];
        foreach ($positionsArray as $position) {
            if ($position) {
                $sequenceLength = $position['end'] - $position['start'] + 1;
                $sequence = substr($chromosomeDna, $position['start'], $sequenceLength);

                if (1 !== $this->strand) {
                    $sequence = $sequenceManipulator->reverseComplement($sequence);
                }

                if ($html) {
                    $sequences[] = '<span class="'.$position['legend'].'">'.$sequence.'</span>';
                } else {
                    $sequences[] = $sequence;
                }
            }
        }

        $sequence = implode($sequences);

        // Cut the sequence in array with 60 nucleotides per line
        $letters = str_split($sequence, 1);
        $sequence = [];
        $sequence[0] = '';
        $i = 0;
        $l = 0;
        foreach ($letters as $letter) {
            $type = ctype_upper($letter);

            if (!$type) {
                $sequence[$l] .= $letter;
            } elseif ($type && $i < 60) {
                ++$i;
                $sequence[$l] .= $letter;
            } else {
                ++$l;
                $i = 1;
                $sequence[$l] = $letter;
            }
        }

        return $sequence;
    }
}
