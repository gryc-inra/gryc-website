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
 * @ORM\HasLifecycleCallbacks
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
    private $tempId;

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
     * The users for this strain.
     *
     * @var User|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="strains")
     */
    private $users;

    /**
     * @ORM\Column(name="typeStrain", type="boolean")
     */
    private $typeStrain = false;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Reference", mappedBy="strains")
     */
    private $references;

    /**
     * Strain constructor.
     */
    public function __construct()
    {
        $this->synonymes = [];
        $this->chromosomes = new ArrayCollection();
        $this->seos = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->references = new ArrayCollection();
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
     * @return Strain
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
     * @return Strain
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
     * @return Strain
     */
    public function emptySynonymes()
    {
        $this->synonymes = [];

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
        if (!$this->chromosomes->contains($chromosome)) {
            $this->chromosomes[] = $chromosome;
            $chromosome->setStrain($this);
        }

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
        if ($this->chromosomes->contains($chromosome)) {
            $this->chromosomes->removeElement($chromosome);
        }

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
        if (!$this->seos->contains($seo)) {
            $this->seos[] = $seo;
            $seo->setStrain($this);
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
     * @return Strain
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
     * Add user.
     *
     * @param User $user
     *
     * @return $this
     */
    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    /**
     * Remove user.
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return User|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get users id.
     *
     * @return array
     */
    public function getUsersId()
    {
        $usersId = [];

        foreach ($this->users as $user) {
            $usersId[] = $user->getId();
        }

        return $usersId;
    }

    /**
     * Is allowed user ?
     *
     * @param User $user
     *
     * @return bool
     */
    public function isAllowedUser(User $user = null)
    {
        return $this->users->contains($user);
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

    public function addReference(Reference $reference)
    {
        if (!$this->references->contains($reference)) {
            $this->references->add($reference);
        }

        return $this;
    }

    public function removeReference(Reference $reference)
    {
        if ($this->references->contains($reference)) {
            $this->references->removeElement($reference);
        }

        return $this;
    }

    public function getReferences()
    {
        return $this->references;
    }

    /**
     * Before remove.
     *
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tempId = $this->getId();
    }

    /**
     * After remove.
     *
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // Get files
        $files = glob('/var/www/html/files/blast/'.$this->tempId.'_*');

        // Remove files
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
