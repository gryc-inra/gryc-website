<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seo
 *
 * @ORM\Entity
 * @ORM\Table(name="seo")
 */
class Seo
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
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Strain", inversedBy="seos")
     */
    private $strain;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Species", inversedBy="seos")
     */
    private $species;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chromosome", inversedBy="seos")
     */
    private $chromosome;

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
     * Set name
     *
     * @param string $name
     *
     * @return Seo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Seo
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set strain
     *
     * @param Strain $strain
     *
     * @return Seo
     */
    public function setStrain(Strain $strain)
    {
        $this->strain = $strain;

        return $this;
    }

    /**
     * Get strain
     *
     * @return Strain
     */
    public function getStrain()
    {
        return $this->strain;
    }

    /**
     * Set species
     *
     * @param Species $species
     *
     * @return Seo
     */
    public function setSpecies(Species $species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species
     *
     * @return Species
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set chromosome
     *
     * @param Chromosome $species
     *
     * @return Seo
     */
    public function setChromosome(Chromosome $chromosome)
    {
        $this->chromosome = $chromosome;

        return $this;
    }

    /**
     * Get Chromosome
     *
     * @return Species
     */
    public function getChromosome()
    {
        return $this->chromosome;
    }
}
