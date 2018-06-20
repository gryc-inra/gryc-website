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
 * The flat files, linked by chromosomes.
 *
 * @ORM\Entity(repositoryClass="App\Repository\FlatFileRepository")
 */
class FlatFile extends File
{
    /**
     * A human readable name.
     *
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * The file type.
     *
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * Chromosome.
     *
     * @var Chromosome
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Chromosome", inversedBy="flatFiles")
     */
    private $chromosome;

    /**
     * @param $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Chromosome $chromosome
     */
    public function setChromosome(Chromosome $chromosome)
    {
        $this->chromosome = $chromosome;
    }

    /**
     * @return Chromosome
     */
    public function getChromosome()
    {
        return $this->chromosome;
    }

    /**
     * Get upload dir.
     *
     * Return the directory name where files are moved.
     *
     * @return string
     */
    public function getStorageDir()
    {
        return 'flat';
    }
}
