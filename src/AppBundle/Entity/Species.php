<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Species.
 *
 * @ORM\Table(name="species")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpeciesRepository")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Clade", inversedBy="species")
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
     *
     */
    private $lineages;

    /**
     * The taxon ID of the species.
     *
     * @var int
     *
     * @ORM\Column(name="taxid", type="integer", nullable=true, unique=true)
     *
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $taxid;

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
     * An array of synonymes for the species.
     *
     * @var array
     *
     * @ORM\Column(name="synonymes", type="array", nullable=true)
     */
    private $synonymes;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Strain", mappedBy="species", cascade={"persist", "remove"})
     */
    private $strains;

    /**
     * A collection of Seo linked to the species.
     * 
     * @var Seo|ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Seo", mappedBy="species", cascade={"persist", "remove"}, orphanRemoval=true)
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
        $this->synonymes = array();
        $this->lineages = array();
        $this->strains = new ArrayCollection();
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
     * Set clade.
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
     * Get clade.
     *
     * @return Clade
     */
    public function getClade()
    {
        return $this->clade;
    }

    /**
     * Set scientificName.
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
     * Get scientificName.
     *
     * @return string
     */
    public function getScientificName()
    {
        return $this->scientificName;
    }

    /**
     * Set species.
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
     * Get species.
     *
     * @return string
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set genus.
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
     * Get genus.
     *
     * @return string
     */
    public function getGenus()
    {
        return $this->genus;
    }

    /**
     * Add lineage.
     *
     * @param string $lineage
     *
     * @return Species
     */
    public function addLineage($lineage)
    {
        if (!empty($lineage) && !in_array($lineage, $this->lineages, true)) {
            $this->lineages[] = $lineage;
        }

        return $this;
    }

    /**
     * Remove lineage.
     *
     * @param string $lineage
     *
     * @return Species
     */
    public function removeLineage($lineage)
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
     *
     * @return Species
     */
    public function setLineages($lineages)
    {
        $this->lineages = array();

        foreach ($lineages as $lineage) {
            $this->addLineage($lineage);
        }

        return $this;
    }

    /**
     * Empty synonymes.
     *
     * @return Species
     */
    public function emptyLineages()
    {
        $this->lineages = array();

        return $this;
    }

    /**
     * Get lineage.
     *
     * @return array
     */
    public function getLineages()
    {
        return $this->lineages;
    }

    /**
     * Set taxid.
     *
     * @param int $taxid
     *
     * @return Species
     */
    public function setTaxid($taxid)
    {
        $this->taxid = $taxid;

        return $this;
    }

    /**
     * Get taxid.
     *
     * @return int
     */
    public function getTaxid()
    {
        return $this->taxid;
    }

    /**
     * Set geneticCode.
     *
     * @param int $geneticCode
     *
     * @return Species
     */
    public function setGeneticCode($geneticCode)
    {
        $this->geneticCode = $geneticCode;

        return $this;
    }

    /**
     * Get geneticCode.
     *
     * @return int
     */
    public function getGeneticCode()
    {
        return $this->geneticCode;
    }

    /**
     * Set mitoCode.
     *
     * @param int $mitoCode
     *
     * @return Species
     */
    public function setMitoCode($mitoCode)
    {
        $this->mitoCode = $mitoCode;

        return $this;
    }

    /**
     * Get mitoCode.
     *
     * @return int
     */
    public function getMitoCode()
    {
        return $this->mitoCode;
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
     * Get synonymes.
     *
     * @return array
     */
    public function getSynonymes()
    {
        return $this->synonymes;
    }

    /**
     * Set description.
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
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add strain.
     *
     * @param Strain $strain
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
     *
     * @param Strain $strain
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
     *
     * @param Seo $seo
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
     *
     * @param Seo $seo
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
     *
     * @return Species
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
