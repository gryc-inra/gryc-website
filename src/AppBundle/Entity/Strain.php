<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Strain.
 *
 * @ORM\Table(name="strain")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StrainRepository")
 */
class Strain
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="synonymes", type="array")
     */
    private $synonymes;

    /**
     * @var int
     *
     * @ORM\Column(name="length", type="integer")
     */
    private $length;

    /**
     * @var float
     *
     * @ORM\Column(name="gc", type="float")
     */
    private $gc;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="cdsCount", type="integer")
     */
    private $cdsCount;

    /**
     * @var Chromosome
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Chromosome", mappedBy="strain", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $chromosomes;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Species", inversedBy="strains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $species;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Seo", mappedBy="strain", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $seos;

    public function __construct()
    {
        $this->synonymes = array();
        $this->chromosomes = new ArrayCollection();
        $this->seos = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return Strain
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
     * Add synonym.
     *
     * @param string $synonym
     *
     * @return Species
     */
    public function addSynonym($synonym)
    {
        if (!empty($synonym) && !in_array($synonym, $this->synonymes, true)) {
            $this->synonymes[] = $synonym;
        }

        return $this;
    }

    /**
     * Remove synonym.
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
     * Empty synonymes.
     *
     * @return Species
     */
    public function emptySynonymes()
    {
        $this->synonymes = array();

        return $this;
    }

    /**
     * Set synonymes.
     *
     * @param array $synonymes
     *
     * @return Strain
     */
    public function setSynonymes($synonymes)
    {
        foreach ($synonymes as $synonym) {
            $this->addSynonym($synonym);
        }

        return $this;
    }

    /**
     * Get synonymes.
     *
     * @return array
     */
    public function getSynonymes()
    {
        return $this->synonymes;
    }

    /**
     * Set length.
     *
     * @param int $length
     *
     * @return Strain
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length.
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set gc.
     *
     * @param float $gc
     *
     * @return Strain
     */
    public function setGc($gc)
    {
        $this->gc = $gc;

        return $this;
    }

    /**
     * Get gc.
     *
     * @return float
     */
    public function getGc()
    {
        return $this->gc;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Strain
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set cdsCount.
     *
     * @param int $cdsCount
     *
     * @return Strain
     */
    public function setCdsCount($cdsCount)
    {
        $this->cdsCount = $cdsCount;

        return $this;
    }

    /**
     * Get cdsCount.
     *
     * @return int
     */
    public function getCdsCount()
    {
        return $this->cdsCount;
    }

    /**
     * Add chromosomes.
     *
     * @param Chromosome $chromosome
     *
     * @return $this
     */
    public function addChromosome(Chromosome $chromosome)
    {
        $this->chromosomes[] = $chromosome;
        $chromosome->setStrain($this);

        return $this;
    }

    /**
     * Remove chromosomes.
     *
     * @param Chromosome $chromosome
     *
     * @return $this
     */
    public function removeChromosome(Chromosome $chromosome)
    {
        $this->chromosomes->removeElement($chromosome);

        return $this;
    }

    /**
     * Get chromosomes.
     *
     * @return Chromosome|ArrayCollection
     */
    public function getChromosomes()
    {
        return $this->chromosomes;
    }

    /**
     * Set species.
     *
     * @param Species $species
     *
     * @return $this
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

    /**
     * Add Seo
     *
     * @param Seo $seo
     */
    public function addSeo(Seo $seo)
    {
        $this->seos[] = $seo;
        $seo->setStrain($this);

        return $this;
    }

    /**
     * Remove Seo
     *
     * @param Seo $seo
     */
    public function removeSeo(Seo $seo)
    {
        $this->seos->removeElement($seo);
    }

    /**
     * Get Seo
     *
     * @return ArrayCollection
     */
    public function getSeos()
    {
        return $this->seos;
    }
}
