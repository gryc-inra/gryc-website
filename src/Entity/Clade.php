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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="clade")
 * @ORM\Entity(repositoryClass="App\Repository\CladeRepository")
 */
class Clade
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\Length(min=2)
     * @Assert\Regex("#^[A-Z]#")
     */
    private $name;

    /**
     * @ORM\Column(name="description", type="text")
     * @Assert\NotNull()
     */
    private $description;

    /**
     * @ORM\Column(name="mainClade", type="boolean")
     */
    private $mainClade;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Species", mappedBy="clade", cascade={"remove"})
     */
    private $species;

    public function __construct()
    {
        $this->species = new ArrayCollection();
        $this->mainClade = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setMainClade(bool $mainClade): self
    {
        $this->mainClade = $mainClade;

        return $this;
    }

    public function getMainClade(): bool
    {
        return $this->mainClade;
    }

    public function isMainCladeToString(): string
    {
        if ($this->mainClade) {
            return 'Yes';
        }

        return 'No';
    }

    public function getSpecies(): Collection
    {
        return $this->species;
    }
}
