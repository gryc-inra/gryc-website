<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Strain.
 *
 * @ORM\Table(name="strain")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StrainRepository")
 */
class Strain
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
     * The name of the strain.
     * 
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * An array of synonymes.
     * 
     * @var array
     *
     * @ORM\Column(name="synonymes", type="array")
     */
    private $synonymes;

    /**
     * The length of the strain. (Total of chromosomes length).
     * 
     * @var int
     *
     * @ORM\Column(name="length", type="integer")
     */
    private $length;

    /**
     * The G/C percentage.
     * 
     * @var float
     *
     * @ORM\Column(name="gc", type="float")
     */
    private $gc;

    /**
     * The status of the strain.
     * Eg: complete.
     * 
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * The number of CDS.
     * 
     * @var int
     *
     * @ORM\Column(name="cdsCount", type="integer")
     */
    private $cdsCount;

    /**
     * The owned chromosomes.
     * 
     * @var Chromosome|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Chromosome", mappedBy="strain", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $chromosomes;

    /**
     * The parent species.
     * 
     * @var Species
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Species", inversedBy="strains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $species;

    /**
     * The Seo linked on the species.
     * 
     * @var Seo|ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Seo", mappedBy="strain", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $seos;

    /**
     * The slug, for url.
     * 
     * @var string
     * 
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * Is the strain public ?
     * Eg: true (public) or false (private).
     * 
     * @var bool
     * 
     * @ORM\Column(name="public", type="boolean")
     */
    private $public = false;

    /**
     * The authorized user.
     * For private strains only.
     * 
     * @var User|ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="authorizedStrains")
     */
    private $authorizedUsers;

    /**
     * @ORM\Column(name="typeStrain", type="boolean")
     */
    private $typeStrain = false;

    /**
     * Strain constructor.
     */
    public function __construct()
    {
        $this->synonymes = array();
        $this->chromosomes = new ArrayCollection();
        $this->seos = new ArrayCollection();
        $this->authorizedUsers = new ArrayCollection();
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
     * Add Seo.
     *
     * @param Seo $seo
     * 
     * @return Strain
     */
    public function addSeo(Seo $seo)
    {
        $this->seos[] = $seo;
        $seo->setStrain($this);

        return $this;
    }

    /**
     * Remove Seo.
     *
     * @param Seo $seo
     */
    public function removeSeo(Seo $seo)
    {
        $this->seos->removeElement($seo);
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

    /**
     * Set public.
     * 
     * @param bool $bool
     *
     * @return $this
     */
    public function setPublic($bool)
    {
        $this->public = $bool;

        return $this;
    }

    /**
     * Get public.
     * 
     * @return bool
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Is public?
     * 
     * @return bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * Is private?
     * 
     * @return bool
     */
    public function isPrivate()
    {
        return !$this->isPublic();
    }

    /**
     * Return if the strain is public or no, in letter.
     * 
     * @return string
     */
    public function isPublicToString()
    {
        if ($this->isPublic()) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    /**
     * Return if the strain is private or no, in letter.
     *
     * @return string
     */
    public function isPrivateToString()
    {
        if ($this->isPrivate()) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    /**
     * Add authorized user.
     *
     * @param User $user
     *
     * @return $this
     */
    public function addAuthorizedUser(User $user)
    {
        $user->addAuthorizedStrain($this);
        $this->authorizedUsers[] = $user;

        return $this;
    }

    /**
     * Remove authorized user.
     *
     * @param User $user
     */
    public function removeAuthorizedUser(User $user)
    {
        $user->removeAuthorizedStrain($this);
        $this->authorizedUsers->removeElement($user);
    }

    /**
     * Get authorized users.
     *
     * @return User|ArrayCollection
     */
    public function getAuthorizedUsers()
    {
        return $this->authorizedUsers;
    }

    /**
     * Is authorized user ?
     *
     * @param User $user
     * 
     * @return bool
     */
    public function isAuthorizedUser(User $user = null)
    {
        return $this->authorizedUsers->contains($user);
    }

    /**
     * Set type.
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function setTypeStrain($bool)
    {
        $this->typeStrain = $bool;

        return $this;
    }

    /**
     * Get type.
     *
     * @return bool
     */
    public function getTypeStrain()
    {
        return $this->typeStrain;
    }

    /**
     * Is a type strain?
     *
     * @return bool
     */
    public function isTypeStrain()
    {
        return $this->typeStrain;
    }

    /**
     * Return if the strain is a type strain or not, in letter.
     *
     * @return string
     */
    public function isTypeStrainToString()
    {
        if ($this->isTypeStrain()) {
            return 'yes';
        } else {
            return 'no';
        }
    }
}
