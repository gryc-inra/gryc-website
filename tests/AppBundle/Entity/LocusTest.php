<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Locus;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class LocusTest extends TestCase
{
    /**
     * @var Locus
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
        $this->assertSame($chromosome, $this->locus->getChromosome());

        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->locus->getFeatures()));

        $feature = $this->getMockBuilder('AppBundle\Entity\Feature')
            ->disableOriginalConstructor()
            ->setMethods(['getProductsFeatures'])
            ->getMock();
        $feature
            ->method('getProductsFeatures')
            ->willReturn(new ArrayCollection(['product1', 'product2']));

        $this->locus->addFeature($feature);
        $this->assertSame($feature, $this->locus->getFeatures()->first());

        $this->assertSame(2, $this->locus->countProductNumber());

        $this->locus->removeFeature($feature);
        $this->assertTrue($this->locus->getFeatures()->isEmpty());

        $locus = $this->getMockBuilder('AppBundle\Entity\Locus')
            ->disableOriginalConstructor()
            ->getMock();

        $this->locus->setPreviousLocus($locus);
        $this->assertSame($locus, $this->locus->getPreviousLocus());

        $this->locus->setNextLocus($locus);
        $this->assertSame($locus, $this->locus->getNextLocus());

        $this->locus->setPreviousLocusDistance(1000);
        $this->assertSame(1000, $this->locus->getPreviousLocusDistance());

        $this->locus->setNextLocusDistance(1000);
        $this->assertSame(1000, $this->locus->getNextLocusDistance());
    }
}
