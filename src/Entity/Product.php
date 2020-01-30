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

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product extends GeneticEntry
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Feature", inversedBy="productsFeatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $feature;

    /**
     * @ORM\Column(name="translation", type="text", nullable=true)
     */
    private $translation;

    /**
     * @ORM\Column(name="structure", type="array", nullable=true)
     */
    private $structure;

    public function setFeature(Feature $feature): self
    {
        $this->feature = $feature;

        return $this;
    }

    public function getFeature(): ?Feature
    {
        return $this->feature;
    }

    public function setTranslation(?string $translation)
    {
        $this->translation = $translation;

        return $this;
    }

    public function getTranslation(): ?string
    {
        return $this->translation;
    }

    public function setStructure(?array $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    public function getStructure(): ?array
    {
        return $this->structure;
    }
}
