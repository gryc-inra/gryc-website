<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GeneticEntry.
 *
 * @ORM\MappedSuperclass
 */
class GeneticEntry
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
     * @var int
     *
     * @ORM\Column(name="strand", type="smallint")
     */
    private $strand;

    /**
     * @var array
     *
     * @ORM\Column(name="product", type="array")
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="dbXref", type="object", nullable=true)
     */
    private $dbXref;

    /**
     * @var array
     *
     * @ORM\Column(name="annotation", type="array")
     */
    private $annotation;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var array
     *
     * @ORM\Column(name="coordinates", type="array")
     */
    private $coordinates;

    /**
     * @var int
     *
     * @ORM\Column(name="start", type="integer")
     */
    private $start;

    /**
     * @var int
     *
     * @ORM\Column(name="end", type="integer")
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

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
     * Set strand.
     *
     * @param int $strand
     *
     * @return GeneticEntry
     */
    public function setStrand($strand)
    {
        $this->strand = $strand;

        return $this;
    }

    /**
     * Get strand.
     *
     * @return int
     */
    public function getStrand()
    {
        return $this->strand;
    }

    /**
     * Set product.
     *
     * @param array $product
     *
     * @return GeneticEntry
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return array
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return GeneticEntry
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
     * Set dbXref.
     *
     * @param \stdClass $dbXref
     *
     * @return GeneticEntry
     */
    public function setDbXref($dbXref)
    {
        $this->dbXref = $dbXref;

        return $this;
    }

    /**
     * Get dbXref.
     *
     * @return \stdClass
     */
    public function getDbXref()
    {
        return $this->dbXref;
    }

    /**
     * Set annotation.
     *
     * @param array $annotation
     *
     * @return GeneticEntry
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }

    /**
     * Get annotation.
     *
     * @return array
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return GeneticEntry
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set coordinates.
     *
     * @param array $coordinates
     *
     * @return GeneticEntry
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * Get coordinates.
     *
     * @return array
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set start.
     *
     * @param int $start
     *
     * @return GeneticEntry
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start.
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end.
     *
     * @param int $end
     *
     * @return GeneticEntry
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end.
     *
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set note.
     *
     * @param string $note
     *
     * @return GeneticEntry
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
}
