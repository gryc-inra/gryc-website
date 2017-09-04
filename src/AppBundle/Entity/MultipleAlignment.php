<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="multiple_alignment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MultipleAlignmentRepository")
 */
class MultipleAlignment extends QueuingEntitySuperclass
{
    const NB_KEPT_ALIGNMENT = 10;

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
     * @var string
     *
     * @ORM\Column(name="query", type="text")
     */
    private $query;

    public function __construct()
    {
        parent::__construct();

        $this->query = ">my-query\n";
    }

    public function __clone()
    {
        parent::__clone();

        $this->id = null;
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

    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
