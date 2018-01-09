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

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="blast")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlastRepository")
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
     * @var string
     *
     * @ORM\Column(name="tool", type="string", length=255)
     */
    private $tool;

    /**
     * @var string
     *
     * @ORM\Column(name="db", type="string", length=255)
     */
    private $database;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Strain")
     */
    private $strains;

    /**
     * @var string
     *
     * @ORM\Column(name="query", type="text")
     */
    private $query;

    /**
     * @var bool
     *
     * @ORM\Column(name="filter", type="boolean")
     */
    private $filter;

    /**
     * @var float
     *
     * @ORM\Column(name="evalue", type="float")
     */
    private $evalue;

    /**
     * @var bool
     *
     * @ORM\Column(name="gapped", type="boolean")
     */
    private $gapped;

    public function __construct()
    {
        parent::__construct();
        $this->strains = new ArrayCollection();
        $this->tool = self::DEFAULT_TOOL;
        $this->filter = false;
        $this->evalue = 0.001;
        $this->gapped = true;
    }

    public function __clone()
    {
        parent::__clone();

        $this->id = null;
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

    public function setTool($tool)
    {
        $this->tool = $tool;

        return $this;
    }

    public function getTool()
    {
        return $this->tool;
    }

    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function setStrains($strains)
    {
        $this->strains = $strains;

        return $this;
    }

    public function getStrains()
    {
        return $this->strains;
    }

    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function setEvalue($evalue)
    {
        $this->evalue = $evalue;

        return $this;
    }

    public function getEvalue()
    {
        return $this->evalue;
    }

    public function setGapped($gapped)
    {
        $this->gapped = $gapped;

        return $this;
    }

    public function getGapped()
    {
        return $this->gapped;
    }
}
