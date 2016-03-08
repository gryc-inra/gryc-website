<?php

namespace Grycii\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DnaSequence.
 *
 * @ORM\Table(name="dna_sequence")
 * @ORM\Entity(repositoryClass="Grycii\AppBundle\Repository\DnaSequenceRepository")
 */
class DnaSequence
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
     * @var array
     *
     * @ORM\Column(name="letterCount", type="array")
     */
    private $letterCount;

    /**
     * @var string
     *
     * @ORM\Column(name="dna", type="text")
     */
    private $dna;

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
     * Set letterCount.
     *
     * @param array $letterCount
     *
     * @return DnaSequence
     */
    public function setLetterCount($letterCount)
    {
        $this->letterCount = $letterCount;

        return $this;
    }

    /**
     * Get letterCount.
     *
     * @return array
     */
    public function getLetterCount()
    {
        return $this->letterCount;
    }

    /**
     * Set dna.
     *
     * @param string $dna
     *
     * @return DnaSequence
     */
    public function setDna($dna)
    {
        $this->dna = $dna;

        return $this;
    }

    /**
     * Get dna.
     *
     * @return string
     */
    public function getDna()
    {
        return $this->dna;
    }
}
