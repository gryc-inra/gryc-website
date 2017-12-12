<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Clade.
 *
 * @ORM\Table(name="clade")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CladeRepository")
 */
class Clade
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
     * The name of the clade.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\Length(min=2)
     * @Assert\Regex("#^[A-Z]#")
     */
    private $name;

    /**
     * The description of the clade.
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotNull()
     */
    private $description;

    /**
     * Is it a main clade ?
     * true -> yes, false -> no.
     *
     * @var bool
     *
     * @ORM\Column(name="mainClade", type="boolean")
     */
    private $mainClade;

    /**
     * A collection of species in this clade.
     *
     * @var Species|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Species", mappedBy="clade", cascade={"remove"})
     */
    private $species;

    /**
     * Clade constructor.
     */
    public function __construct()
    {
        $this->species = new ArrayCollection();
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
     * @return Clade
     */
    public function setName(string $name)
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
     * Set description.
     *
     * @param string $description
     *
     * @return Clade
     */
    public function setDescription(string $description)
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
     * Set mainClade.
     *
     * @param bool $mainClade
     *
     * @return Clade
     */
    public function setMainClade(bool $mainClade)
    {
        $this->mainClade = $mainClade;

        return $this;
    }

    /**
     * Get mainClade.
     *
     * @return bool
     */
    public function getMainClade()
    {
        return $this->mainClade;
    }

    /**
     * isMainCladeToString.
     *
     * @return string
     */
    public function isMainCladeToString()
    {
        if ($this->mainClade) {
            return 'Yes';
        }

        return 'No';
    }

    /**
     * Get species.
     *
     * @return Species|ArrayCollection
     */
    public function getSpecies()
    {
        return $this->species;
    }
}
