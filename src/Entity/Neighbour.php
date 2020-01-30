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
 * @ORM\Entity(repositoryClass="App\Repository\NeighbourRepository")
 */
class Neighbour
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locus", inversedBy="neighbours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locus")
     */
    private $neighbour;

    /**
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @ORM\Column(name="number_neighbours", type="integer")
     */
    private $numberNeighbours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setLocus(Locus $locus): self
    {
        $this->locus = $locus;

        return $this;
    }

    public function getLocus(): ?Locus
    {
        return $this->locus;
    }

    public function setNeighbour(?Locus $neighbour): self
    {
        $this->neighbour = $neighbour;

        return $this;
    }

    public function getNeighbour(): ?Locus
    {
        return $this->neighbour;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setNumberNeighbours(int $numberNeighbours)
    {
        $this->numberNeighbours = $numberNeighbours;

        return $this;
    }

    public function getNumberNeighbours(): ?int
    {
        return $this->numberNeighbours;
    }
}
