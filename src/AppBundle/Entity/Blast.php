<?php

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
        $this->tool = 'blastp';
        $this->database = 'cds_prot';
        $this->query = ">my-query\n";
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
