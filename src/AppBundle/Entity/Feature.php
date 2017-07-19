<?php

namespace AppBundle\Entity;

use AppBundle\Utils\SequenceManipulator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeatureRepository")
 */
class Feature extends GeneticEntry
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Locus", inversedBy="features")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locus;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="feature", cascade={"persist", "remove"})
     */
    private $productsFeatures;

    public function __construct()
    {
        $this->productsFeatures = new ArrayCollection();
    }

    public function setLocus(Locus $locus)
    {
        $this->locus = $locus;

        return $this;
    }

    public function getLocus()
    {
        return $this->locus;
    }

    public function addProductsFeatures(Product $product)
    {
        if (!$this->productsFeatures->contains($product)) {
            $this->productsFeatures->add($product);
            $product->setFeature($this);
        }

        return $this;
    }

    public function removeProductsFeatures(Product $product)
    {
        if ($this->productsFeatures->contains($product)) {
            $this->productsFeatures->removeElement($product);
        }

        return $this;
    }

    public function getProductsFeatures()
    {
        return $this->productsFeatures;
    }
}
