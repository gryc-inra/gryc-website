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
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Locus
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Locus", inversedBy="neighbours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locus;

    /**
     * @var Locus
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Locus")
     */
    private $neighbour;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var int
     *
     * @ORM\Column(name="number_neighbours", type="integer")
     */
    private $numberNeighbours;

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set locus.
     */
    public function setLocus(Locus $locus): self
    {
        $this->locus = $locus;

        return $this;
    }

    /**
     * Get locus.
     */
    public function getLocus(): \App\Entity\Locus
    {
        return $this->locus;
    }

    /**
     * Set neighbour.
     *
     * @param Locus $neighbour
     */
    public function setNeighbour(Locus $neighbour = null): self
    {
        $this->neighbour = $neighbour;

        return $this;
    }

    /**
     * Get neighbour.
     */
    public function getNeighbour(): \App\Entity\Locus
    {
        return $this->neighbour;
    }

    /**
     * Set position.
     *
     * @param string $position
     */
    public function setPosition($position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Set number of neighbours.
     *
     * @param $numberNeighbours
     *
     * @return $this
     */
    public function setNumberNeighbours(int $numberNeighbours)
    {
        $this->numberNeighbours = $numberNeighbours;

        return $this;
    }

    /**
     * Get number of neighbours.
     */
    public function getNumberNeighbours(): int
    {
        return $this->numberNeighbours;
    }
}
