<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chromosome.
 *
 * @ORM\Table(name="chromosome")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChromosomeRepository")
 */
class Chromosome
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="accessions", type="array", nullable=true)
     */
    private $accessions;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(name="keywords", type="array")
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="projectId", type="string", length=255)
     */
    private $projectId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var int
     *
     * @ORM\Column(name="numCreated", type="integer", nullable=true)
     */
    private $numCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReleased", type="datetime")
     */
    private $dateReleased;

    /**
     * @var int
     *
     * @ORM\Column(name="numReleased", type="integer", nullable=true)
     */
    private $numReleased;

    /**
     * @var int
     *
     * @ORM\Column(name="numVersion", type="integer", nullable=true)
     */
    private $numVersion;

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
     * @var int
     *
     * @ORM\Column(name="cdsCount", type="integer")
     */
    private $cdsCount;

    /**
     * @var bool
     *
     * @ORM\Column(name="mitochondrial", type="boolean")
     */
    private $mitochondrial;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var Strain
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Strain", inversedBy="chromosomes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $strain;

    /**
     * @var DnaSequence
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\DnaSequence", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $dnaSequence;

    /**
     * Chromosome constructor.
     */
    public function __construct()
    {
        $this->accessions = array();
        $this->keywords = array();
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
     * @return Chromosome
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
     * Add accession.
     *
     * @param string $accession
     *
     * @return Chromosome
     */
    public function addAccession($accession)
    {
        if (!empty($accession) && !in_array($accession, $this->accessions, true)) {
            $this->accessions[] = $accession;
        }

        return $this;
    }

    /**
     * Remove accession.
     *
     * @var string
     *
     * @return Chromosome
     */
    public function removeAccession($accession)
    {
        if (false !== $key = array_search($accession, $this->accessions, true)) {
            unset($this->accessions[$key]);
            $this->accessions = array_values($this->accessions);
        }

        return $this;
    }

    /**
     * Empty accessions.
     *
     * @return Chromosome
     */
    public function emptyAccessions()
    {
        $this->accessions = array();

        return $this;
    }

    /**
     * Set accession.
     *
     * @param array $accessions
     *
     * @return Chromosome
     */
    public function setAccession($accessions)
    {
        foreach ($accessions as $accession) {
            $this->addAccession($accession);
        }

        return $this;
    }

    /**
     * Get accession.
     *
     * @return array
     */
    public function getAccessions()
    {
        return $this->accessions;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Chromosome
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
     * Add keyword.
     *
     * @param string $keyword
     *
     * @return Chromosome
     */
    public function addKeyword($keyword)
    {
        if (!empty($keyword) && !in_array($keyword, $this->keywords)) {
            $this->keywords[] = $keyword;
        }

        return $this;
    }

    /**
     * Remove keyword.
     *
     * @var string
     *
     * @return Chromosome
     */
    public function removeKeyword($keyword)
    {
        if (false !== $key = array_search($keyword, $this->keywords)) {
            unset($this->keywords[$key]);
            $this->keywords = array_values($this->keywords);
        }

        return $this;
    }

    /**
     * Empty keywords.
     *
     * @return Chromosome
     */
    public function emptyKeywords()
    {
        $this->keywords = array();

        return $this;
    }

    /**
     * Set keywords.
     *
     * @param array $keywords
     *
     * @return Chromosome
     */
    public function setKeywords($keywords)
    {
        foreach ($keywords as $keyword) {
            $this->addKeyword($keyword);
        }

        return $this;
    }

    /**
     * Get keywords.
     *
     * @return array
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set projectId.
     *
     * @param string $projectId
     *
     * @return Chromosome
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get projectId.
     *
     * @return string
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set dateCreated.
     *
     * @param \DateTime $dateCreated
     *
     * @return Chromosome
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated.
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set numCreated.
     *
     * @param int $numCreated
     *
     * @return Chromosome
     */
    public function setNumCreated($numCreated)
    {
        $this->numCreated = $numCreated;

        return $this;
    }

    /**
     * Get numCreated.
     *
     * @return int
     */
    public function getNumCreated()
    {
        return $this->numCreated;
    }

    /**
     * Set dateReleased.
     *
     * @param \DateTime $dateReleased
     *
     * @return Chromosome
     */
    public function setDateReleased($dateReleased)
    {
        $this->dateReleased = $dateReleased;

        return $this;
    }

    /**
     * Get dateReleased.
     *
     * @return \DateTime
     */
    public function getDateReleased()
    {
        return $this->dateReleased;
    }

    /**
     * Set numReleased.
     *
     * @param int $numReleased
     *
     * @return Chromosome
     */
    public function setNumReleased($numReleased)
    {
        $this->numReleased = $numReleased;

        return $this;
    }

    /**
     * Get numReleased.
     *
     * @return int
     */
    public function getNumReleased()
    {
        return $this->numReleased;
    }

    /**
     * Set numVersion.
     *
     * @param int $numVersion
     *
     * @return Chromosome
     */
    public function setNumVersion($numVersion)
    {
        $this->numVersion = $numVersion;

        return $this;
    }

    /**
     * Get numVersion.
     *
     * @return int
     */
    public function getNumVersion()
    {
        return $this->numVersion;
    }

    /**
     * Set length.
     *
     * @param int $length
     *
     * @return Chromosome
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
     * @return Chromosome
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
     * Set cdsCount.
     *
     * @param int $cdsCount
     *
     * @return Chromosome
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
     * Set mitochondrial.
     *
     * @param bool $mitochondrial
     *
     * @return Chromosome
     */
    public function setMitochondrial($mitochondrial)
    {
        $this->mitochondrial = $mitochondrial;

        return $this;
    }

    /**
     * Get mitochondrial.
     *
     * @return bool
     */
    public function getMitochondrial()
    {
        return $this->mitochondrial;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return Chromosome
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set strain.
     *
     * @param Strain $strain
     *
     * @return $this
     */
    public function setStrain(Strain $strain)
    {
        $this->strain = $strain;

        return $this;
    }

    /**
     * Get strain.
     *
     * @return Strain
     */
    public function getStrain()
    {
        return $this->strain;
    }

    /**
     * Set DnaSequence.
     *
     * @param DnaSequence $dnaSequence
     *
     * @return $this
     */
    public function setDnaSequence(DnaSequence $dnaSequence)
    {
        $this->dnaSequence = $dnaSequence;

        return $this;
    }

    /**
     * Get DnaSequence.
     *
     * @return DnaSequence
     */
    public function getDnaSequence()
    {
        return $this->dnaSequence;
    }
}
