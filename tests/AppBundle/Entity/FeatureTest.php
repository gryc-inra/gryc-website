<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Feature;
use PHPUnit\Framework\TestCase;

class FeatureTest extends TestCase
{
    public function testAddProductsFeatures()
    {
        $feature = new Feature();
        $product = $this->getMockBuilder('AppBundle\Entity\Product')
            ->disableOriginalConstructor()
            ->getMock();
        $product2 = $this->getMockBuilder('AppBundle\Entity\Product')
            ->disableOriginalConstructor()
            ->getMock();

        // Test the default collection is empty
        $this->assertTrue($feature->getProductsFeatures()->isEmpty());

        // Test add a product
        $feature->addProductsFeatures($product);
        $this->assertSame($product, $feature->getProductsFeatures()->first());

        // Test add a second product
        $feature->addProductsFeatures($product2);
        $this->assertSame($product, $feature->getProductsFeatures()->first());
        $this->assertSame($product2, $feature->getProductsFeatures()->next());

        // Test add an existant object
        $feature->addProductsFeatures($product);
        $this->assertSame(2, $feature->getProductsFeatures()->count());
    }

    public function removeProductsFeatures()
    {
        $feature = new Feature();
        $product = $this->getMockBuilder('AppBundle\Entity\Product')
            ->disableOriginalConstructor()
            ->getMock();
        $product2 = $this->getMockBuilder('AppBundle\Entity\Product')
            ->disableOriginalConstructor()
            ->getMock();

        $feature->addProductsFeatures($product);
        $feature->addProductsFeatures($product2);

        // Test remove an existing object
        $feature->removeProductsFeatures($product);
        $this->assertSame(1, $feature->getProductsFeatures()->count());

        // Test remove an already removed object
        $feature->removeProductsFeatures($product);
        $this->assertSame(1, $feature->getProductsFeatures()->count());
    }
}
