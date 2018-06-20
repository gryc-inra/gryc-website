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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reference.
 *
 * @ORM\Table(name="reference")
 * @ORM\Entity(repositoryClass="App\Repository\ReferenceRepository")
 */
class Reference
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
     * @var array
     *
     * @ORM\Column(name="authors", type="array")
     */
    private $authors;

    /**
     * @var string
     *
     * @ORM\Column(name="container", type="string", length=255)
     */
    private $container;

    /**
     * @var string
     *
     * @ORM\Column(name="doi", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $doi;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     */
    private $url;

    /**
     * @var int
     *
     * @ORM\Column(name="issued", type="integer")
     */
    private $issued;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Locus", inversedBy="references")
     */
    private $locus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Strain", inversedBy="references")
     */
    private $strains;

    public function __construct()
    {
        $this->locus = new ArrayCollection();
        $this->strains = new ArrayCollection();
    }

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
     * Set authors.
     *
     * @param array $authors
     *
     * @return Reference
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * Get authors.
     *
     * @return array
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Set container.
     *
     * @param string $container
     *
     * @return Reference
     */
    public function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get container.
     *
     * @return string
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set doi.
     *
     * @param string $doi
     *
     * @return Reference
     */
    public function setDoi($doi)
    {
        $this->doi = $doi;

        return $this;
    }

    /**
     * Get doi.
     *
     * @return string
     */
    public function getDoi()
    {
        return $this->doi;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return Reference
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set issued.
     *
     * @param int $issued
     *
     * @return Reference
     */
    public function setIssued($issued)
    {
        $this->issued = $issued;

        return $this;
    }

    /**
     * Get issued.
     *
     * @return int
     */
    public function getIssued()
    {
        return $this->issued;
    }

    public function addLocus(Locus $locus)
    {
        if (!$this->locus->contains($locus)) {
            $this->locus->add($locus);
            $locus->addReference($this);
        }

        return $this;
    }

    public function removeLocus(Locus $locus)
    {
        if ($this->locus->contains($locus)) {
            $this->locus->removeElement($locus);
        }

        return $this;
    }

    public function getLocus()
    {
        return $this->locus;
    }

    public function addStrain(Strain $strain)
    {
        if (!$this->strains->contains($strain)) {
            $this->strains->add($strain);
            $strain->addReference($this);
        }

        return $this;
    }

    public function removeStrain(Strain $strain)
    {
        if ($this->strains->contains($strain)) {
            $this->strains->removeElement($strain);
        }

        return $this;
    }

    public function getStrains()
    {
        return $this->strains;
    }
}
