<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The flat files, linked by chromosomes.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FlatFileRepository")
 */
class FlatFile extends File
{
    /**
     * A human readable name.
     *
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * The file type.
     *
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * Chromosome.
     *
     * @var Chromosome
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chromosome", inversedBy="flatFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chromosome;

    /**
     * @param $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Chromosome $chromosome
     */
    public function setChromosome(Chromosome $chromosome)
    {
        $this->chromosome = $chromosome;
    }

    /**
     * @return Chromosome
     */
    public function getChromosome()
    {
        return $this->chromosome;
    }

    /**
     * Get upload dir.
     *
     * Return the directory name where files are moved.
     *
     * @return string
     */
    public function getStorageDir()
    {
        return 'flatFiles';
    }
}
