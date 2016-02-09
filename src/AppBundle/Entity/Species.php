<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Species
 *
 * @ORM\Table(name="species")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpeciesRepository")
 */
class Species
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Clade")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clade;

    /**
     * @var string
     *
     * @ORM\Column(name="scientificName", type="string", length=255, unique=true)
     */
    private $scientificName;

    /**
     * @var string
     *
     * @ORM\Column(name="species", type="string", length=255, unique=true)
     */
    private $species;

    /**
     * @var string
     *
     * @ORM\Column(name="genus", type="string", length=255)
     */
    private $genus;

    /**
     * @var string
     *
     * @ORM\Column(name="lineage", type="string", length=255)
     */
    private $lineage;

    /**
     * @var int
     *
     * @ORM\Column(name="taxid", type="integer", nullable=true, unique=true)
     */
    private $taxid;

    /**
     * @var int
     *
     * @ORM\Column(name="geneticCode", type="integer")
     */
    private $geneticCode;

    /**
     * @var string
     *
     * @ORM\Column(name="mitoCode", type="integer", length=255)
     */
    private $mitoCode;

    /**
     * @var string
     *
     * @ORM\Column(name="synonymes", type="array", nullable=true)
     */
    private $synonymes;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->synonymes = array();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set clade
     *
     * @param Clade $clade
     *
     * @return Species
     */
    public function setClade(Clade $clade)
    {
        $this->clade = $clade;

        return $this;
    }

    /**
     * Get clade
     *
     * @return Clade
     */
    public function getClade()
    {
        return $this->clade;
    }

    /**
     * Set scientificName
     *
     * @param string $scientificName
     *
     * @return Species
     */
    public function setScientificName($scientificName)
    {
        $this->scientificName = $scientificName;

        return $this;
    }

    /**
     * Get scientificName
     *
     * @return string
     */
    public function getScientificName()
    {
        return $this->scientificName;
    }

    /**
     * Set species
     *
     * @param string $species
     *
     * @return Species
     */
    public function setSpecies($species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species
     *
     * @return string
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set genus
     *
     * @param string $genus
     *
     * @return Species
     */
    public function setGenus($genus)
    {
        $this->genus = $genus;

        return $this;
    }

    /**
     * Get genus
     *
     * @return string
     */
    public function getGenus()
    {
        return $this->genus;
    }

    /**
     * Set lineage
     *
     * @param string $lineage
     *
     * @return Species
     */
    public function setLineage($lineage)
    {
        $this->lineage = $lineage;

        return $this;
    }

    /**
     * Get lineage
     *
     * @return string
     */
    public function getLineage()
    {
        return $this->lineage;
    }

    /**
     * Set taxid
     *
     * @param integer $taxid
     *
     * @return Species
     */
    public function setTaxid($taxid)
    {
        $this->taxid = $taxid;

        return $this;
    }

    /**
     * Get taxid
     *
     * @return int
     */
    public function getTaxid()
    {
        return $this->taxid;
    }

    /**
     * Set geneticCode
     *
     * @param integer $geneticCode
     *
     * @return Species
     */
    public function setGeneticCode($geneticCode)
    {
        $this->geneticCode = $geneticCode;

        return $this;
    }

    /**
     * Get geneticCode
     *
     * @return int
     */
    public function getGeneticCode()
    {
        return $this->geneticCode;
    }

    /**
     * Set mitoCode
     *
     * @param string $mitoCode
     *
     * @return Species
     */
    public function setMitoCode($mitoCode)
    {
        $this->mitoCode = $mitoCode;

        return $this;
    }

    /**
     * Get mitoCode
     *
     * @return integer
     */
    public function getMitoCode()
    {
        return $this->mitoCode;
    }

    /**
     * Add synonym
     *
     * @param string $synonym
     *
     * @return Species
     */
    public function addSynonym($synonym)
    {
        if (!in_array($synonym, $this->synonymes, true)) {
            $this->synonymes[] = $synonym;
        }

        return $this;
    }

    /**
     * Remove synonym
     *
     * @param string $synonym
     *
     * @return Species
     */
    public function removeSynonym($synonym)
    {
        if (false !== $key = array_search($synonym, $this->synonymes, true)) {
            unset($this->synonymes[$key]);
            $this->synonymes = array_values($this->synonymes);
        }

        return $this;
    }

    /**
     * Empty synonymes
     *
     * @return Species
     */
    public function emptySynonymes()
    {
        $this->synonymes = array();

        return $this;
    }

    /**
     * Set synonymes
     *
     * @return Species
     */
    public function setSynonymes($synonymes)
    {
        $this->synonymes = array();

        foreach ($synonymes as $synonym) {
            $this->addSynonym($synonym);
        }

        return $this;
    }

    /**
     * Get synonymes
     *
     * @return array
     */
    public function getSynonymes()
    {
        return $this->synonymes;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Species
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
