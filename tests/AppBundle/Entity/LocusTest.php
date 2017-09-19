<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Locus;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class LocusTest extends TestCase
{
    /**
     * @var Locus $locus
     */
    private $locus;

    public function setUp()
    {
        $this->locus = new Locus();
    }

    public function tearDown()
    {
        $this->locus = null;
    }

    public function testSetterAndGetter()
    {
        $chromosome = $this->getMockBuilder('AppBundle\Entity\Chromosome')
            ->disableOriginalConstructor()
            ->getMock();
        $this->locus->setChromosome($chromosome);
        $this->assertEquals($chromosome, $this->locus->getChromosome());

        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->locus->getFeatures()));

        $feature = $this->getMockBuilder('AppBundle\Entity\Feature')
            ->disableOriginalConstructor()
            ->setMethods(['getProductsFeatures'])
            ->getMock();
        $feature
            ->method('getProductsFeatures')
            ->willReturn(new ArrayCollection(['product1', 'product2']));

        $this->locus->addFeature($feature);
        $this->assertEquals($feature, $this->locus->getFeatures()->first());

        $this->assertEquals(2, $this->locus->countProductNumber());

        $this->locus->removeFeature($feature);
        $this->assertEquals(true, $this->locus->getFeatures()->isEmpty());

        $locus = $this->getMockBuilder('AppBundle\Entity\Locus')
            ->disableOriginalConstructor()
            ->getMock();

        $this->locus->setPreviousLocus($locus);
        $this->assertEquals($locus, $this->locus->getPreviousLocus());

        $this->locus->setNextLocus($locus);
        $this->assertEquals($locus, $this->locus->getNextLocus());

        $this->locus->setPreviousLocusDistance(1000);
        $this->assertEquals(1000, $this->locus->getPreviousLocusDistance());

        $this->locus->setNextLocusDistance(1000);
        $this->assertEquals(1000, $this->locus->getNextLocusDistance());
    }
}
