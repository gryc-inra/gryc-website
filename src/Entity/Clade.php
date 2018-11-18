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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Clade.
 *
 * @ORM\Table(name="clade")
 * @ORM\Entity(repositoryClass="App\Repository\CladeRepository")
 */
class Clade
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
     * The name of the clade.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\Length(min=2)
     * @Assert\Regex("#^[A-Z]#")
     */
    private $name;

    /**
     * The description of the clade.
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotNull()
     */
    private $description;

    /**
     * Is it a main clade ?
     * true -> yes, false -> no.
     *
     * @var bool
     *
     * @ORM\Column(name="mainClade", type="boolean")
     */
    private $mainClade;

    /**
     * A collection of species in this clade.
     *
     * @var Species|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Species", mappedBy="clade", cascade={"remove"})
     */
    private $species;

    /**
     * Clade constructor.
     */
    public function __construct()
    {
        $this->species = new ArrayCollection();
    }

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set name.
     */
    public function setName(string $name): self
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
     * Set description.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set mainClade.
     */
    public function setMainClade(bool $mainClade): self
    {
        $this->mainClade = $mainClade;

        return $this;
    }

    /**
     * Get mainClade.
     */
    public function getMainClade(): bool
    {
        return $this->mainClade;
    }

    /**
     * isMainCladeToString.
     */
    public function isMainCladeToString(): string
    {
        if ($this->mainClade) {
            return 'Yes';
        }

        return 'No';
    }

    /**
     * Get species.
     *
     * @return Species|ArrayCollection
     */
    public function getSpecies()
    {
        return $this->species;
    }
}
