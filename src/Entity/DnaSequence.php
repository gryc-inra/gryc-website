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
 * @ORM\Table(name="dna_sequence")
 * @ORM\Entity(repositoryClass="App\Repository\DnaSequenceRepository")
 */
class DnaSequence
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="letterCount", type="array")
     */
    private $letterCount;

    public function __construct()
    {
        $this->letterCount = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setLetterCount(array $letterCount): self
    {
        $this->letterCount = $letterCount;

        return $this;
    }

    public function getLetterCount(): array
    {
        return $this->letterCount;
    }
}
