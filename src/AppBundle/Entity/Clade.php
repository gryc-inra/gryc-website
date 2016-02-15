<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clade.
 *
 * @ORM\Table(name="clade")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CladeRepository")
 */
class Clade
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="mainClade", type="boolean")
     */
    private $mainClade;

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
     * Set description.
     *
     * @param string $description
     *
     * @return Clade
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
     * Set mainClade.
     *
     * @param bool $mainClade
     *
     * @return Clade
     */
    public function setMainClade($mainClade)
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
        } else {
            return 'No';
        }
    }
}
