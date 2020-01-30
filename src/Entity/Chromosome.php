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

/**
 * @ORM\Table(name="chromosome")
 * @ORM\Entity(repositoryClass="App\Repository\ChromosomeRepository")
 */
class Chromosome
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="accessions", type="array", nullable=true)
     */
    private $accessions;

    /**
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(name="keywords", type="array")
     */
    private $keywords;

    /**
     * @ORM\Column(name="projectId", type="string", length=255, nullable=true)
     */
    private $projectId;

    /**
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(name="numCreated", type="integer", nullable=true)
     */
    private $numCreated;

    /**
     * @ORM\Column(name="dateReleased", type="datetime")
     */
    private $dateReleased;

    /**
     * @ORM\Column(name="numReleased", type="integer", nullable=true)
     */
    private $numReleased;

    /**
     * @ORM\Column(name="numVersion", type="integer", nullable=true)
     */
    private $numVersion;

    /**
     * @ORM\Column(name="length", type="integer")
     */
    private $length;

    /**
     * @ORM\Column(name="gc", type="float")
     */
    private $gc;

    /**
     * @ORM\Column(name="cdsCount", type="integer")
     */
    private $cdsCount;

    /**
     * @ORM\Column(name="mitochondrial", type="boolean")
     */
    private $mitochondrial;

    /**
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain", inversedBy="chromosomes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $strain;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DnaSequence", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $dnaSequence;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FlatFile", mappedBy="chromosome", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $flatFiles;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Locus", mappedBy="chromosome", cascade={"persist", "remove"})
     */
    private $locus;

    public function __construct()
    {
        $this->accessions = [];
        $this->keywords = [];
        $this->flatFiles = new ArrayCollection();
        $this->seos = new ArrayCollection();
        $this->locus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function addAccession(string $accession): self
    {
        if (!empty($accession) && !\in_array($accession, $this->accessions, true)) {
            $this->accessions[] = $accession;
        }

        return $this;
    }

    public function removeAccession(string $accession): self
    {
        if (false !== $key = array_search($accession, $this->accessions, true)) {
            unset($this->accessions[$key]);
            $this->accessions = array_values($this->accessions);
        }

        return $this;
    }

    public function emptyAccessions(): self
    {
        $this->accessions = [];

        return $this;
    }

    public function setAccession(array $accessions): self
    {
        if (null !== $accessions) {
            foreach ($accessions as $accession) {
                $this->addAccession($accession);
            }
        }

        return $this;
    }

    public function getAccessions(): array
    {
        return $this->accessions;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function addKeyword(string $keyword): self
    {
        if (!empty($keyword) && !\in_array($keyword, $this->keywords, true)) {
            $this->keywords[] = $keyword;
        }

        return $this;
    }

    public function removeKeyword(string $keyword): self
    {
        if (false !== $key = array_search($keyword, $this->keywords, true)) {
            unset($this->keywords[$key]);
            $this->keywords = array_values($this->keywords);
        }

        return $this;
    }

    public function emptyKeywords(): self
    {
        $this->keywords = [];

        return $this;
    }

    public function setKeywords(array $keywords): self
    {
        if (null === $keywords) {
            return $this;
        }

        foreach ($keywords as $keyword) {
            $this->addKeyword($keyword);
        }

        return $this;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function setProjectId(?string $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    public function setDateCreated(\DateTime $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    public function setNumCreated(?int $numCreated): self
    {
        $this->numCreated = $numCreated;

        return $this;
    }

    public function getNumCreated(): ?int
    {
        return $this->numCreated;
    }

    public function setDateReleased(\DateTime $dateReleased): self
    {
        $this->dateReleased = $dateReleased;

        return $this;
    }

    public function getDateReleased(): \DateTime
    {
        return $this->dateReleased;
    }

    public function setNumReleased(?int $numReleased): self
    {
        $this->numReleased = $numReleased;

        return $this;
    }

    public function getNumReleased(): ?int
    {
        return $this->numReleased;
    }

    public function setNumVersion(?int $numVersion): self
    {
        $this->numVersion = $numVersion;

        return $this;
    }

    public function getNumVersion(): ?int
    {
        return $this->numVersion;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setGc(float $gc): self
    {
        $this->gc = $gc;

        return $this;
    }

    public function getGc(): ?float
    {
        return $this->gc;
    }

    public function setCdsCount(int $cdsCount): self
    {
        $this->cdsCount = $cdsCount;

        return $this;
    }

    public function getCdsCount(): ?int
    {
        return $this->cdsCount;
    }

    public function setMitochondrial(bool $mitochondrial): self
    {
        $this->mitochondrial = $mitochondrial;

        return $this;
    }

    public function getMitochondrial(): ?bool
    {
        return $this->mitochondrial;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setStrain(Strain $strain): self
    {
        $this->strain = $strain;

        return $this;
    }

    public function getStrain(): ?Strain
    {
        return $this->strain;
    }

    public function setDnaSequence(DnaSequence $dnaSequence): self
    {
        $this->dnaSequence = $dnaSequence;

        return $this;
    }

    public function getDnaSequence(): ?DnaSequence
    {
        return $this->dnaSequence;
    }

    public function addFlatFile(FlatFile $flatFile): self
    {
        if (!$this->flatFiles->contains($flatFile)) {
            $this->flatFiles[] = $flatFile;
            $flatFile->setChromosome($this);
        }

        return $this;
    }

    public function removeFlatFile(FlatFile $flatFile): self
    {
        if ($this->flatFiles->contains($flatFile)) {
            $this->flatFiles->removeElement($flatFile);
        }

        return $this;
    }

    public function getFlatFiles(): Collection
    {
        return $this->flatFiles;
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

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function addLocus(Locus $locus): self
    {
        if (!$this->locus->contains($locus)) {
            $this->locus[] = $locus;
            $locus->setChromosome($this);
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
}
