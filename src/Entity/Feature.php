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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeatureRepository")
 */
class Feature extends GeneticEntry
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locus", inversedBy="features")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locus;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="feature", cascade={"persist", "remove"})
     */
    private $productsFeatures;

    /**
     * @ORM\Column(name="structure", type="array", nullable=true)
     */
    private $structure;

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

    public function countProductFeatures()
    {
        return $this->productsFeatures->count();
    }

    /**
     * Set structure.
     *
     * @param array $structure
     */
    public function setStructure($structure): GeneticEntry
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure.
     */
    public function getStructure(): array
    {
        return $this->structure;
    }
}
