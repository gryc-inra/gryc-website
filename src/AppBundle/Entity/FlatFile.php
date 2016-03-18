<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FlatFileRepository")
 */
class FlatFile extends CopiedFile
{
    /**
     * @var string
     *
     * @ORM\Column(name="molType", type="string", length=255)
     */
    private $molType;

    /**
     * @var string
     *
     * @ORM\Column(name="featureType", type="string", length=255)
     */
    private $featureType;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=255)
     */
    private $format;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chromosome", inversedBy="flatFiles")
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
     * Set molType
     *
     * @param string $molType
     *
     * @return FlatFile
     */
    public function setMolType($molType)
    {
        $this->molType = $molType;

        return $this;
    }

    /**
     * Get molType
     *
     * @return string
     */
    public function getMolType()
    {
        return $this->molType;
    }

    /**
     * Set featureType
     *
     * @param string $featureType
     *
     * @return FlatFile
     */
    public function setFeatureType($featureType)
    {
        $this->featureType = $featureType;

        return $this;
    }

    /**
     * Get featureType
     *
     * @return string
     */
    public function getFeatureType()
    {
        return $this->featureType;
    }

    /**
     * Set format
     *
     * @param string $format
     *
     * @return FlatFile
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set Chromosome
     *
     * @param Chromosome $chromosome
     *
     * @return FlatFile
     */
    public function setChromosome(Chromosome $chromosome)
    {
        $this->chromosome = $chromosome;

        return $this;
    }

    /**
     * Get Chromosome
     *
     * @return Chromosome
     */
    public function getChromosome()
    {
        return $this->chromosome;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../protected-files/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'flatFiles';
    }
}
