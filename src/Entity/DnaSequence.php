<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DnaSequence.
 *
 * @ORM\Table(name="dna_sequence")
 * @ORM\Entity(repositoryClass="App\Repository\DnaSequenceRepository")
 */
class DnaSequence
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
     * The number of letters in the sequence.
     *
     * @var array
     *
     * @ORM\Column(name="letterCount", type="array")
     */
    private $letterCount;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set letterCount.
     *
     * @param array $letterCount
     *
     * @return DnaSequence
     */
    public function setLetterCount($letterCount)
    {
        $this->letterCount = $letterCount;

        return $this;
    }

    /**
     * Get letterCount.
     *
     * @return array
     */
    public function getLetterCount()
    {
        return $this->letterCount;
    }
}
