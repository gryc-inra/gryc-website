<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocusRepository")
 */
class Locus extends GeneticEntry
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chromosome", inversedBy="locus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chromosome;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Feature", mappedBy="locus", cascade={"persist", "remove"})
     */
    private $features;

    /**
     * @ORM\Column(name="previous_locus", type="string", length=255, nullable=true)
     */
    private $previousLocus;

    /**
     * @ORM\Column(name="next_locus", type="string", length=255, nullable=true)
     */
    private $nextLocus;

    /**
     * @ORM\Column(name="previous_locus_distance", type="integer", nullable=true)
     */
    private $previousLocusDistance;

    /**
     * @ORM\Column(name="next_locus_distance", type="integer", nullable=true)
     */
    private $nextLocusDistance;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Reference", mappedBy="locus")
     */
    private $references;

    public function __construct()
    {
        $this->features = new ArrayCollection();
        $this->references = new ArrayCollection();
    }

    public function setChromosome(Chromosome $chromosome)
    {
        $this->chromosome = $chromosome;

        return $this;
    }

    public function getChromosome()
    {
        return $this->chromosome;
    }

    public function addFeature(Feature $feature)
    {
        if (!$this->features->contains($feature)) {
            $this->features->add($feature);
            $feature->setLocus($this);
        }

        return $this;
    }

    public function removeFeature(Feature $feature)
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
        }

        return $this;
    }

    public function getFeatures()
    {
        return $this->features;
    }

    public function countProductNumber()
    {
        $nbProduct = 0;
        foreach ($this->features as $feature) {
            $nbProduct += $feature->getProductsFeatures()->count();
        }

        return $nbProduct;
    }

    public function setPreviousLocus($name)
    {
        $this->previousLocus = $name;

        return $this;
    }

    public function getPreviousLocus()
    {
        return $this->previousLocus;
    }

    public function setNextLocus($name)
    {
        $this->nextLocus = $name;

        return $this;
    }

    public function getNextLocus()
    {
        return $this->nextLocus;
    }

    public function setPreviousLocusDistance($distance)
    {
        $this->previousLocusDistance = $distance;

        return $this;
    }

    public function getPreviousLocusDistance()
    {
        return $this->previousLocusDistance;
    }

    public function setNextLocusDistance($distance)
    {
        $this->nextLocusDistance = $distance;

        return $this;
    }

    public function getNextLocusDistance()
    {
        return $this->nextLocusDistance;
    }

    public function addReference(Reference $reference)
    {
        if (!$this->references->contains($reference)) {
            $this->references->add($reference);
        }

        return $this;
    }

    public function removeReference(Reference $reference)
    {
        if ($this->references->contains($reference)) {
            $this->references->removeElement($reference);
        }

        return $this;
    }

    public function getReferences()
    {
        return $this->references;
    }
}
