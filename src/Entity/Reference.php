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
 * @ORM\Table(name="reference")
 * @ORM\Entity(repositoryClass="App\Repository\ReferenceRepository")
 */
class Reference
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="authors", type="array")
     */
    private $authors;

    /**
     * @ORM\Column(name="container", type="string", length=255)
     */
    private $container;

    /**
     * @ORM\Column(name="doi", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $doi;

    /**
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     */
    private $url;

    /**
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setAuthors(array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setContainer(string $container): self
    {
        $this->container = $container;

        return $this;
    }

    public function getContainer(): ?string
    {
        return $this->container;
    }

    public function setDoi(string $doi): self
    {
        $this->doi = $doi;

        return $this;
    }

    public function getDoi(): ?string
    {
        return $this->doi;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setIssued(int $issued): self
    {
        $this->issued = $issued;

        return $this;
    }

    public function getIssued(): ?int
    {
        return $this->issued;
    }

    public function addLocus(Locus $locus): self
    {
        if (!$this->locus->contains($locus)) {
            $this->locus->add($locus);
            $locus->addReference($this);
        }

        return $this;
    }

    public function removeLocus(Locus $locus): self
    {
        if ($this->locus->contains($locus)) {
            $this->locus->removeElement($locus);
        }

        return $this;
    }

    public function getLocus(): Collection
    {
        return $this->locus;
    }

    public function addStrain(Strain $strain): self
    {
        if (!$this->strains->contains($strain)) {
            $this->strains->add($strain);
            $strain->addReference($this);
        }

        return $this;
    }

    public function removeStrain(Strain $strain): self
    {
        if ($this->strains->contains($strain)) {
            $this->strains->removeElement($strain);
        }

        return $this;
    }

    public function getStrains(): Collection
    {
        return $this->strains;
    }
}
