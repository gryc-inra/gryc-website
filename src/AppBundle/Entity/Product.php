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

    /**
     * @ORM\Column(name="translation", type="text", nullable=true)
     */
    private $translation;

    public function setFeature(Feature $feature)
    {
        $this->feature = $feature;

        return $this;
    }

    public function getFeature()
    {
        return $this->feature;
    }

    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    public function getTranslation()
    {
        return $this->translation;
    }
}
