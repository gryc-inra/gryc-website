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

/**
 * @ORM\Table(name="blast")
 * @ORM\Entity(repositoryClass="App\Repository\BlastRepository")
 */
class Blast extends QueuingEntitySuperclass
{
    const NB_KEPT_BLAST = 10;

    const DATABASES_NAMES = [
        'cds_prot' => 'CDS (prot)',
        'cds_nucl' => 'CDS (nucl)',
        'chr' => 'Chromosomes',
    ];

    const TOOLS_DATABASES = [
        'blastn' => ['cds_nucl', 'chr'],
        'blastp' => ['cds_prot'],
        'tblastn' => ['cds_nucl', 'chr'],
        'blastx' => ['cds_prot'],
        'tblastx' => ['cds_nucl', 'chr'],
    ];

    const TOOLS_DEFAULT_DATABASE = [
        'blastn' => 'cds_nucl',
        'blastp' => 'cds_prot',
        'tblastn' => 'cds_nucl',
        'blastx' => 'cds_prot',
        'tblastx' => 'cds_nucl',
    ];

    const DEFAULT_TOOL = 'blastp';

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="tool", type="string", length=255)
     */
    private $tool;

    /**
     * @ORM\Column(name="db", type="string", length=255)
     */
    private $database;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Strain")
     */
    private $strains;

    /**
     * @ORM\Column(name="query", type="text")
     */
    private $query;

    /**
     * @ORM\Column(name="filter", type="boolean")
     */
    private $filter;

    /**
     * @ORM\Column(name="evalue", type="float")
     */
    private $evalue;

    /**
     * @ORM\Column(name="gapped", type="boolean")
     */
    private $gapped;

    public function __construct()
    {
        parent::__construct();

        $this->strains = new ArrayCollection();
        $this->tool = self::DEFAULT_TOOL;
        $this->database = self::TOOLS_DEFAULT_DATABASE[$this->tool];
        $this->filter = false;
        $this->evalue = 0.001;
        $this->gapped = true;
    }

    public function __clone()
    {
        parent::__clone();

        $this->id = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTool(string $tool): self
    {
        $this->tool = $tool;

        return $this;
    }

    public function getTool(): ?string
    {
        return $this->tool;
    }

    public function setDatabase(string $database): self
    {
        $this->database = $database;

        return $this;
    }

    public function getDatabase(): ?string
    {
        return $this->database;
    }

    public function setStrains(Collection $strains): self
    {
        $this->strains = $strains;

        return $this;
    }

    public function getStrains(): Collection
    {
        return $this->strains;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setFilter(bool $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    public function getFilter(): bool
    {
        return $this->filter;
    }

    public function setEvalue(float $evalue): self
    {
        $this->evalue = $evalue;

        return $this;
    }

    public function getEvalue(): ?float
    {
        return $this->evalue;
    }

    public function setGapped(bool $gapped): self
    {
        $this->gapped = $gapped;

        return $this;
    }

    public function getGapped(): bool
    {
        return $this->gapped;
    }
}
