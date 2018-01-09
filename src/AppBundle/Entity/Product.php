<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
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

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product extends GeneticEntry
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Feature", inversedBy="productsFeatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $feature;

    /**
     * @ORM\Column(name="translation", type="text", nullable=true)
     */
    private $translation;

    public function setFeature(Feature $feature)
    {
        $this->feature = $feature;

        return $this;
    }

    public function getFeature()
    {
        return $this->feature;
    }

    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    public function getTranslation()
    {
        return $this->translation;
    }
}
