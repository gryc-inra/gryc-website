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
 * Seo.
 *
 * @ORM\Entity(repositoryClass="App\Repository\SeoRepository")
 * @ORM\Table(name="seo")
 */
class Seo
{
    /**
     * The ID in the database.
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The name attribut.
     * <meta name="" content="" />.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * The content attribut.
     * <meta name="" content="" />.
     *
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * The concerned strain.
     * Strain or Species or Chromosome.
     *
     * @var Strain
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain", inversedBy="seos")
     */
    private $strain;

    /**
     * The concerned species.
     * Species or Strain or Chromosome.
     *
     * @var Species
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Species", inversedBy="seos")
     */
    private $species;

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set content.
     *
     * @param string $content
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set strain.
     */
    public function setStrain(Strain $strain): self
    {
        $this->strain = $strain;

        return $this;
    }

    /**
     * Get strain.
     */
    public function getStrain(): Strain
    {
        return $this->strain;
    }

    /**
     * Set species.
     */
    public function setSpecies(Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species.
     */
    public function getSpecies(): Species
    {
        return $this->species;
    }
}
