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
 * @ORM\Entity(repositoryClass="App\Repository\BlastFileRepository")
 */
class BlastFile extends File
{
    /**
     * Strain.
     *
     * @var Strain
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain", inversedBy="blastFiles")
     */
    private $strain;

    /**
     * @param Strain $strain
     */
    public function setStrain(Strain $strain)
    {
        $this->strain = $strain;
    }

    /**
     * @return Strain
     */
    public function getStrain()
    {
        return $this->strain;
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
        return 'blast';
    }
}
