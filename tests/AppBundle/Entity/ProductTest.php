<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @var Product
     */
    private $product;

    public function setUp()
    {
        $this->product = new Product();
    }

    public function tearDown()
    {
        $this->product = null;
    }

    public function testSetterAndGetter()
    {
        $feature = $this->getMockBuilder('AppBundle\Entity\Feature')
            ->disableOriginalConstructor()
            ->getMock();
        $this->product->setFeature($feature);
        $this->assertEquals($feature, $this->product->getFeature());

        $this->product->setTranslation('EEEEEEE');
        $this->assertEquals('EEEEEEE', $this->product->getTranslation());
    }
}
