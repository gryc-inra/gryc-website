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

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocusRepository")
 */
class Locus extends GeneticEntry
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chromosome", inversedBy="locus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chromosome;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Feature", mappedBy="locus", cascade={"persist", "remove"})
     */
    private $features;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference", mappedBy="locus")
     */
    private $references;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Neighbour", mappedBy="locus", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $neighbours;

    /**
     * @ORM\Column(name="context", type="string", length=255)
     */
    private $context;

    /**
     * @ORM\Column(name="sequence", type="text")
     */
    private $sequence;

    /**
     * @ORM\Column(name="upstream_sequence", type="text")
     */
    private $upstreamSequence;

    /**
     * @ORM\Column(name="downstream_sequence", type="text")
     */
    private $downstreamSequence;

    public function __construct()
    {
        $this->features = new ArrayCollection();
        $this->references = new ArrayCollection();
        $this->neighbours = new ArrayCollection();
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

    public function countFeatures()
    {
        return $this->features->count();
    }

    public function hasFeatures()
    {
        return $this->countFeatures() > 0;
    }

    public function getProductFeatures()
    {
        $productFeatures = [];
        foreach ($this->features as $feature) {
            $productFeatures[] = $feature->getProductsFeatures()->toArray();
        }

        $productFeatures = !empty($productFeatures) ? \call_user_func_array('array_merge', $productFeatures) : [];

        return new ArrayCollection($productFeatures);
    }

    public function countProductFeatures()
    {
        $nbProduct = 0;
        foreach ($this->features as $feature) {
            $nbProduct += $feature->countProductFeatures();
        }

        return $nbProduct;
    }

    public function hasProductFeatures()
    {
        return $this->countProductFeatures() > 0;
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

    public function addNeighbour(Neighbour $neighbour)
    {
        if (!$this->neighbours->contains($neighbour)) {
            $neighbour->setLocus($this);
            $this->neighbours->add($neighbour);
        }

        return $this;
    }

    public function removeNeighbour(Neighbour $neighbour)
    {
        if ($this->neighbours->contains($neighbour)) {
            $this->neighbours->removeElement($neighbour);
        }

        return $this;
    }

    public function clearNeighbours()
    {
        $this->neighbours->clear();

        return $this;
    }

    public function getNeighbours()
    {
        return $this->neighbours;
    }

    /**
     * Set context.
     *
     * @param string $context
     *
     * @return Locus
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context.
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set sequence.
     *
     * @param string $sequence
     *
     * @return Locus
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get locus sequence.
     *
     * @return string
     */
    public function getLocusSequence()
    {
        return $this->sequence;
    }

    /**
     * Set upstreamSequence.
     *
     * @param string $upstreamSequence
     *
     * @return Locus
     */
    public function setUpstreamSequence($upstreamSequence)
    {
        $this->upstreamSequence = $upstreamSequence;

        return $this;
    }

    /**
     * Get upstreamSequence.
     *
     * @return string
     */
    public function getUpstreamSequence()
    {
        return $this->upstreamSequence;
    }

    /**
     * Set downstreamSequence.
     *
     * @param string $downstreamSequence
     *
     * @return Locus
     */
    public function setDownstreamSequence($downstreamSequence)
    {
        $this->downstreamSequence = $downstreamSequence;

        return $this;
    }

    /**
     * Get downstreamSequence.
     *
     * @return string
     */
    public function getDownstreamSequence()
    {
        return $this->downstreamSequence;
    }
}
