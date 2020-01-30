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
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="species")
 * @ORM\Entity(repositoryClass="App\Repository\SpeciesRepository")
 * @UniqueEntity(fields="scientificName", message="A species already exists with this scientific name.")
 * @UniqueEntity(fields="species", message="A species already exists with this species name.")
 */
class Species
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Clade", inversedBy="species")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clade;

    /**
     * @ORM\Column(name="scientificName", type="string", length=255, unique=true)
     * @Assert\Regex("#^[A-Z][a-z]* [a-z]*$#", message="The scientific name is in two word, the first begin with a capital letter and the second word is in small letters. (eg: Saccharomyces cerevisiae)")
     */
    private $scientificName;

    /**
     * @ORM\Column(name="genus", type="string", length=255)
     * @Assert\Regex("#^[A-Z][a-z]*$#", message="The genus begin with a capital letter. (eg: Saccharomyces)")
     */
    private $genus;

    /**
     * @ORM\Column(name="species", type="string", length=255, unique=true)
     * @Assert\Regex("#^[a-z]*$#", message="The species is in small letters. (eg: cerevisiae)")
     */
    private $species;

    /**
     * @ORM\Column(name="lineages", type="array")
     */
    private $lineages;

    /**
     * @ORM\Column(name="tax_id", type="integer", nullable=true, unique=true)
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $taxId;

    /**
     * @ORM\Column(name="geneticCode", type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $geneticCode;

    /**
     * @ORM\Column(name="mitoCode", type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $mitoCode;

    /**
     * @ORM\Column(name="synonyms", type="array", nullable=true)
     */
    private $synonyms;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Strain", mappedBy="species", cascade={"persist", "remove"})
     */
    private $strains;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seo", mappedBy="species", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $seos;

    /**
     * @Gedmo\Slug(fields={"scientificName"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->mitoCode = 3;
        $this->geneticCode = 1;
        $this->synonyms = [];
        $this->lineages = [];
        $this->strains = new ArrayCollection();
        $this->seos = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setClade(Clade $clade): self
    {
        $this->clade = $clade;

        return $this;
    }

    public function getClade(): ?Clade
    {
        return $this->clade;
    }

    public function setScientificName(string $scientificName): self
    {
        $this->scientificName = $scientificName;

        return $this;
    }

    public function getScientificName(): ?string
    {
        return $this->scientificName;
    }

    public function setSpecies(string $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setGenus(string $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function addLineage(string $lineage): self
    {
        if (!empty($lineage) && !\in_array($lineage, $this->lineages, true)) {
            $this->lineages[] = $lineage;
        }

        return $this;
    }

    public function removeLineage(string $lineage): self
    {
        if (false !== $key = array_search($lineage, $this->lineages, true)) {
            unset($this->lineages[$key]);
            $this->lineages = array_values($this->lineages);
        }

        return $this;
    }

    public function setLineages(array $lineages): self
    {
        $this->lineages = [];

        foreach ($lineages as $lineage) {
            $this->addLineage($lineage);
        }

        return $this;
    }

    public function emptyLineages(): self
    {
        $this->lineages = [];

        return $this;
    }

    public function getLineages(): array
    {
        return $this->lineages;
    }

    public function setTaxId(?int $taxId): self
    {
        $this->taxId = $taxId;

        return $this;
    }

    public function getTaxId(): ?int
    {
        return $this->taxId;
    }

    public function setGeneticCode(int $geneticCode): self
    {
        $this->geneticCode = $geneticCode;

        return $this;
    }

    public function getGeneticCode(): ?int
    {
        return $this->geneticCode;
    }

    public function setMitoCode(int $mitoCode): self
    {
        $this->mitoCode = $mitoCode;

        return $this;
    }

    public function getMitoCode(): ?int
    {
        return $this->mitoCode;
    }

    public function addSynonym(string $synonym): self
    {
        if (!empty($synonym) && !\in_array($synonym, $this->synonyms, true)) {
            $this->synonyms[] = $synonym;
        }

        return $this;
    }

    public function removeSynonym(string $synonym): self
    {
        if (false !== $key = array_search($synonym, $this->synonyms, true)) {
            unset($this->synonyms[$key]);
            $this->synonyms = array_values($this->synonyms);
        }

        return $this;
    }

    public function emptySynonyms(): self
    {
        $this->synonyms = [];

        return $this;
    }

    public function setSynonyms(array $synonyms): self
    {
        $this->synonyms = [];

        foreach ($synonyms as $synonym) {
            $this->addSynonym($synonym);
        }

        return $this;
    }

    public function getSynonyms(): array
    {
        return $this->synonyms;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function addStrain(Strain $strain): self
    {
        if (!$this->strains->contains($strain)) {
            $this->strains[] = $strain;
            $strain->setSpecies($this);
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

    public function addSeo(Seo $seo): self
    {
        if (!$this->seos->contains($seo)) {
            $this->seos[] = $seo;
            $seo->setSpecies($this);
        }

        return $this;
    }

    public function removeSeo(Seo $seo): self
    {
        if ($this->seos->contains($seo)) {
            $this->seos->removeElement($seo);
        }

        return $this;
    }

    public function getSeos(): Collection
    {
        return $this->seos;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
