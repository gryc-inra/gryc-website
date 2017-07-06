<?php

namespace AppBundle\Entity;

use AppBundle\Utils\SequenceManipulator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeatureRepository")
 */
class Feature extends GeneticEntry
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Locus", inversedBy="features")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locus;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="feature", cascade={"persist", "remove"})
     */
    private $productsFeatures;

    public function __construct()
    {
        $this->productsFeatures = new ArrayCollection();
    }

    public function setLocus(Locus $locus)
    {
        $this->locus = $locus;

        return $this;
    }

    public function getLocus()
    {
        return $this->locus;
    }

    public function addProductsFeatures(Product $product)
    {
        if (!$this->productsFeatures->contains($product)) {
            $this->productsFeatures->add($product);
            $product->setFeature($this);
        }

        return $this;
    }

    public function removeProductsFeatures(Product $product)
    {
        if ($this->productsFeatures->contains($product)) {
            $this->productsFeatures->removeElement($product);
        }

        return $this;
    }

    public function getProductsFeatures()
    {
        return $this->productsFeatures;
    }

    public function getSequence($showUtr = true, $showIntron = true, $upstream = 0, $downstream = 0, $html = true)
    {
        $chromosomeDna = $this->getLocus()->getChromosome()->getDnaSequence()->getDna();
        $locusStart = $this->getLocus()->getStart();
        $locusEnd = $this->getLocus()->getEnd();

        // Create a positionsArray
        $positionsArray = [];

        // First, define the upstream
        if (($upstream > 0 && 1 === $this->getLocus()->getStrand()) || ($downstream > 0 && 1 !== $this->getLocus()->getStrand())) {
            $stream = (1 === $this->getLocus()->getStrand()) ? $upstream : $downstream;

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
        $firstExonCoord = explode('..', $this->getLocus()->getCoordinates()[0]);
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
        $nbExons = count($this->getLocus()->getCoordinates());
        $nbIntrons = $nbExons - 1;
        $haveIntron = $nbIntrons > 0 ? true : false;
        foreach ($this->getLocus()->getCoordinates() as $coordinate) {
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
        $lastExonCoord = explode('..', $this->getLocus()->getCoordinates()[$nbExons - 1]);
        // If the last exon end position is smaller than the locus end position, there is a 3'UTR
        if (true === $showUtr && $lastExonCoord[1] < $locusEnd) {
            $positionsArray['3UTR']['start'] = $lastExonCoord[1] + 1;
            $positionsArray['3UTR']['end'] = $locusEnd;
            $positionsArray['3UTR']['legend'] = 'utr';
        } else {
            $positionsArray['3UTR'] = false;
        }

        // Then, define the downstream
        if (($downstream > 0 && 1 === $this->getLocus()->getStrand()) || ($upstream > 0 && 1 !== $this->getLocus()->getStrand())) {
            $stream = (1 === $this->getLocus()->getStrand()) ? $downstream : $upstream;

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

        if (1 !== $this->getLocus()->getStrand()) {
            $positionsArray = array_reverse($positionsArray);
            $sequenceManipulator = new SequenceManipulator();
        }

        $sequences = [];
        foreach ($positionsArray as $position) {
            if ($position) {
                $sequenceLength = $position['end'] - $position['start'] + 1;
                $sequence = substr($chromosomeDna, $position['start'], $sequenceLength);

                if (1 !== $this->getLocus()->getStrand()) {
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
