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
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Species.
 *
 * @ORM\Table(name="species")
 * @ORM\Entity(repositoryClass="App\Repository\SpeciesRepository")
 * @UniqueEntity(fields="scientificName", message="A species already exists with this scientific name.")
 * @UniqueEntity(fields="species", message="A species already exists with this species name.")
 */
class Species
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
     * The clade including the species.
     *
     * @var Clade
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Clade", inversedBy="species")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clade;

    /**
     * The scientific name of the species.
     *
     * @var string
     *
     * @ORM\Column(name="scientificName", type="string", length=255, unique=true)
     * @Assert\Regex("#^[A-Z][a-z]* [a-z]*$#", message="The scientific name is in two word, the first begin with a capital letter and the second word is in small letters. (eg: Saccharomyces cerevisiae)")
     */
    private $scientificName;

    /**
     * The genus of the species.
     *
     * @var string
     *
     * @ORM\Column(name="genus", type="string", length=255)
     * @Assert\Regex("#^[A-Z][a-z]*$#", message="The genus begin with a capital letter. (eg: Saccharomyces)")
     */
    private $genus;

    /**
     * The species name.
     *
     * @var string
     *
     * @ORM\Column(name="species", type="string", length=255, unique=true)
     * @Assert\Regex("#^[a-z]*$#", message="The species is in small letters. (eg: cerevisiae)")
     */
    private $species;

    /**
     * An array of lineages.
     *
     * @var array
     *
     * @ORM\Column(name="lineages", type="array")
     */
    private $lineages;

    /**
     * The taxon ID of the species.
     *
     * @var int
     *
     * @ORM\Column(name="tax_id", type="integer", nullable=true, unique=true)
     *
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $taxId;

    /**
     * The genetic code of the species.
     *
     * @var int
     *
     * @ORM\Column(name="geneticCode", type="integer")
     *
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $geneticCode;

    /**
     * The mito code of the species.
     *
     * @var int
     *
     * @ORM\Column(name="mitoCode", type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $mitoCode;

    /**
     * An array of synonyms for the species.
     *
     * @var array
     *
     * @ORM\Column(name="synonyms", type="array", nullable=true)
     */
    private $synonyms;

    /**
     * The description of the species.
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * A collection of strains owned by the species.
     *
     * @var Strain|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Strain", mappedBy="species", cascade={"persist", "remove"})
     */
    private $strains;

    /**
     * A collection of Seo linked to the species.
     *
     * @var Seo|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Seo", mappedBy="species", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $seos;

    /**
     * A slug, for url.
     *
     * @Gedmo\Slug(fields={"scientificName"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * Species constructor.
     */
    public function __construct()
    {
        $this->mitoCode = 3;
        $this->geneticCode = 1;
        $this->synonyms = [];
        $this->lineages = [];
        $this->strains = new ArrayCollection();
        $this->seos = new ArrayCollection();
    }

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set clade.
     */
    public function setClade(Clade $clade): self
    {
        $this->clade = $clade;

        return $this;
    }

    /**
     * Get clade.
     */
    public function getClade(): Clade
    {
        return $this->clade;
    }

    /**
     * Set scientificName.
     *
     * @param string $scientificName
     */
    public function setScientificName($scientificName): self
    {
        $this->scientificName = $scientificName;

        return $this;
    }

    /**
     * Get scientificName.
     */
    public function getScientificName(): string
    {
        return $this->scientificName;
    }

    /**
     * Set species.
     *
     * @param string $species
     */
    public function setSpecies($species): self
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species.
     */
    public function getSpecies(): string
    {
        return $this->species;
    }

    /**
     * Set genus.
     *
     * @param string $genus
     */
    public function setGenus($genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    /**
     * Get genus.
     */
    public function getGenus(): string
    {
        return $this->genus;
    }

    /**
     * Add lineage.
     *
     * @param string $lineage
     */
    public function addLineage($lineage): self
    {
        if (!empty($lineage) && !\in_array($lineage, $this->lineages, true)) {
            $this->lineages[] = $lineage;
        }

        return $this;
    }

    /**
     * Remove lineage.
     *
     * @param string $lineage
     */
    public function removeLineage($lineage): self
    {
        if (false !== $key = array_search($lineage, $this->lineages, true)) {
            unset($this->lineages[$key]);
            $this->lineages = array_values($this->lineages);
        }

        return $this;
    }

    /**
     * Set lineages.
     *
     * @param array $lineages
     */
    public function setLineages($lineages): self
    {
        $this->lineages = [];

        foreach ($lineages as $lineage) {
            $this->addLineage($lineage);
        }

        return $this;
    }

    /**
     * Empty lineages.
     */
    public function emptyLineages(): self
    {
        $this->lineages = [];

        return $this;
    }

    /**
     * Get lineage.
     */
    public function getLineages(): array
    {
        return $this->lineages;
    }

    /**
     * Set taxId.
     *
     * @param int $taxId
     */
    public function setTaxId($taxId): self
    {
        $this->taxId = $taxId;

        return $this;
    }

    /**
     * Get taxId.
     */
    public function getTaxId(): int
    {
        return $this->taxId;
    }

    /**
     * Set geneticCode.
     *
     * @param int $geneticCode
     */
    public function setGeneticCode($geneticCode): self
    {
        $this->geneticCode = $geneticCode;

        return $this;
    }

    /**
     * Get geneticCode.
     */
    public function getGeneticCode(): int
    {
        return $this->geneticCode;
    }

    /**
     * Set mitoCode.
     *
     * @param int $mitoCode
     */
    public function setMitoCode($mitoCode): self
    {
        $this->mitoCode = $mitoCode;

        return $this;
    }

    /**
     * Get mitoCode.
     */
    public function getMitoCode(): int
    {
        return $this->mitoCode;
    }

    /**
     * Add synonym.
     *
     * @param string $synonym
     */
    public function addSynonym($synonym): self
    {
        if (!empty($synonym) && !\in_array($synonym, $this->synonyms, true)) {
            $this->synonyms[] = $synonym;
        }

        return $this;
    }

    /**
     * Remove synonym.
     *
     * @param string $synonym
     */
    public function removeSynonym($synonym): self
    {
        if (false !== $key = array_search($synonym, $this->synonyms, true)) {
            unset($this->synonyms[$key]);
            $this->synonyms = array_values($this->synonyms);
        }

        return $this;
    }

    /**
     * Empty synonyms.
     */
    public function emptySynonyms(): self
    {
        $this->synonyms = [];

        return $this;
    }

    /**
     * Set synonyms.
     *
     * @param array $synonyms
     */
    public function setSynonyms($synonyms): self
    {
        $this->synonyms = [];

        foreach ($synonyms as $synonym) {
            $this->addSynonym($synonym);
        }

        return $this;
    }

    /**
     * Get synonyms.
     */
    public function getSynonyms(): array
    {
        return $this->synonyms;
    }

    /**
     * Set description.
     *
     * @param string $description
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Add strain.
     *
     *
     * @return $this
     */
    public function addStrain(Strain $strain)
    {
        if (!$this->strains->contains($strain)) {
            $this->strains[] = $strain;
            $strain->setSpecies($this);
        }

        return $this;
    }

    /**
     * Remove strain.
     */
    public function removeStrain(Strain $strain)
    {
        if ($this->strains->contains($strain)) {
            $this->strains->removeElement($strain);
        }

        return $this;
    }

    /**
     * Get strain.
     *
     * @return Strain|ArrayCollection
     */
    public function getStrains()
    {
        return $this->strains;
    }

    /**
     * Add Seo.
     */
    public function addSeo(Seo $seo)
    {
        if (!$this->seos->contains($seo)) {
            $this->seos[] = $seo;
            $seo->setSpecies($this);
        }

        return $this;
    }

    /**
     * Remove Seo.
     */
    public function removeSeo(Seo $seo)
    {
        if ($this->seos->contains($seo)) {
            $this->seos->removeElement($seo);
        }

        return $this;
    }

    /**
     * Get Seo.
     *
     * @return Seo|ArrayCollection
     */
    public function getSeos()
    {
        return $this->seos;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
}
