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

    public function __construct()
    {
        $this->features = new ArrayCollection();
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
}
