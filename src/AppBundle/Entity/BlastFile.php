<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The flat files, linked by chromosomes.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlastFileRepository")
 */
class BlastFile extends File
{
    /**
     * Strain.
     *
     * @var Strain
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Strain", inversedBy="blastFiles")
     */
    private $strain;

    /**
     * @param Strain $strain
     */
    public function setStrain(Strain $strain)
    {
        $this->strain = $strain;
    }

    /**
     * @return Strain
     */
    public function getStrain()
    {
        return $this->strain;
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
        return 'blast';
    }
}
