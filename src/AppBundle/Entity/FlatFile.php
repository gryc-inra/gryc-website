<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The flat files, linked by chromosomes.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FlatFileRepository")
 */
class FlatFile extends CopiedFile
{
    /**
     * The molecule type.
     * Eg: nuc or prot.
     *
     * @var string
     *
     * @ORM\Column(name="molType", type="string", length=255)
     */
    private $molType;

    /**
     * The feature type.
     * Eg: CDS, Chromosome or Orf.
     *
     * @var string
     *
     * @ORM\Column(name="featureType", type="string", length=255)
     */
    private $featureType;

    /**
     * The file format.
     * Eg: embl or fasta.
     *
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=255)
     */
    private $format;

    /**
     * The concerned chromosome.
     *
     * @var Chromosome
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chromosome", inversedBy="flatFiles")
     */
    private $chromosome;

    /**
     * Set molType.
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
     * Get molType.
     *
     * @return string
     */
    public function getMolType()
    {
        return $this->molType;
    }

    /**
     * Set featureType.
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
     * Get featureType.
     *
     * @return string
     */
    public function getFeatureType()
    {
        return $this->featureType;
    }

    /**
     * Set format.
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
     * Get format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set Chromosome.
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
     * Get Chromosome.
     *
     * @return Chromosome
     */
    public function getChromosome()
    {
        return $this->chromosome;
    }

    /**
     * Get the upload root directory.
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return realpath(__DIR__.'/../../../files/'.$this->getUploadDir());
    }

    /**
     * Get the upload directory.
     *
     * @return string
     */
    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'flatFiles';
    }
}
