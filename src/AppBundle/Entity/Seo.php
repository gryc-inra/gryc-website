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
 * Seo.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SeoRepository")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Strain", inversedBy="seos")
     */
    private $strain;

    /**
     * The concerned species.
     * Species or Strain or Chromosome.
     *
     * @var Species
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Species", inversedBy="seos")
     */
    private $species;

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
     * Set name.
     *
     * @param string $name
     *
     * @return Seo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Seo
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set strain.
     *
     * @param Strain $strain
     *
     * @return Seo
     */
    public function setStrain(Strain $strain)
    {
        $this->strain = $strain;

        return $this;
    }

    /**
     * Get strain.
     *
     * @return Strain
     */
    public function getStrain()
    {
        return $this->strain;
    }

    /**
     * Set species.
     *
     * @param Species $species
     *
     * @return Seo
     */
    public function setSpecies(Species $species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species.
     *
     * @return Species
     */
    public function getSpecies()
    {
        return $this->species;
    }
}
