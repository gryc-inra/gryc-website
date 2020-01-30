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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    public function setChromosome(Chromosome $chromosome): self
    {
        $this->chromosome = $chromosome;

        return $this;
    }

    public function getChromosome(): Chromosome
    {
        return $this->chromosome;
    }

    public function addFeature(Feature $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features->add($feature);
            $feature->setLocus($this);
        }

        return $this;
    }

    public function removeFeature(Feature $feature): self
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
        }

        return $this;
    }

    public function getFeatures(): ?Feature
    {
        return $this->features;
    }

    public function countFeatures(): int
    {
        return $this->features->count();
    }

    public function hasFeatures(): bool
    {
        return $this->countFeatures() > 0;
    }

    public function getProductFeatures(): Collection
    {
        $productFeatures = [];
        foreach ($this->features as $feature) {
            $productFeatures[] = $feature->getProductsFeatures()->toArray();
        }

        $productFeatures = !empty($productFeatures) ? \call_user_func_array('array_merge', $productFeatures) : [];

        return new ArrayCollection($productFeatures);
    }

    public function countProductFeatures(): int
    {
        $nbProduct = 0;
        foreach ($this->features as $feature) {
            $nbProduct += $feature->countProductFeatures();
        }

        return $nbProduct;
    }

    public function hasProductFeatures(): bool
    {
        return $this->countProductFeatures() > 0;
    }

    public function addReference(Reference $reference): self
    {
        if (!$this->references->contains($reference)) {
            $this->references->add($reference);
        }

        return $this;
    }

    public function removeReference(Reference $reference): self
    {
        if ($this->references->contains($reference)) {
            $this->references->removeElement($reference);
        }

        return $this;
    }

    public function getReferences(): ?Reference
    {
        return $this->references;
    }

    public function addNeighbour(Neighbour $neighbour): self
    {
        if (!$this->neighbours->contains($neighbour)) {
            $neighbour->setLocus($this);
            $this->neighbours->add($neighbour);
        }

        return $this;
    }

    public function removeNeighbour(Neighbour $neighbour): self
    {
        if ($this->neighbours->contains($neighbour)) {
            $this->neighbours->removeElement($neighbour);
        }

        return $this;
    }

    public function clearNeighbours(): self
    {
        $this->neighbours->clear();

        return $this;
    }

    public function getNeighbours(): Collection
    {
        return $this->neighbours;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setSequence(string $sequence): self
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function getLocusSequence(): ?string
    {
        return $this->sequence;
    }

    public function setUpstreamSequence(string $upstreamSequence): self
    {
        $this->upstreamSequence = $upstreamSequence;

        return $this;
    }

    public function getUpstreamSequence(): ?string
    {
        return $this->upstreamSequence;
    }

    public function setDownstreamSequence(string $downstreamSequence): self
    {
        $this->downstreamSequence = $downstreamSequence;

        return $this;
    }

    public function getDownstreamSequence(): ?string
    {
        return $this->downstreamSequence;
    }
}
