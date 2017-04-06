<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product extends GeneticEntry
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Feature", inversedBy="productsFeatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $feature;

    public function setFeature(Feature $feature)
    {
        $this->feature = $feature;

        return $this;
    }

    public function getFeature()
    {
        return $this->feature;
    }
}
