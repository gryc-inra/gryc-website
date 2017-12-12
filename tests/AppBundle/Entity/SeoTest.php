<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Seo;
use PHPUnit\Framework\TestCase;

class SeoTest extends TestCase
{
    /**
     * @var Seo
     */
    private $seo;

    public function setUp()
    {
        $this->seo = new Seo();
    }

    public function tearDown()
    {
        $this->seo = null;
    }

    public function testSetterAndGetter()
    {
        $this->assertNull($this->seo->getId());

        $this->seo->setName('name');
        $this->assertSame('name', $this->seo->getName());

        $this->seo->setContent('content');
        $this->assertSame('content', $this->seo->getContent());

        $strain = $this->getMockBuilder('AppBundle\Entity\Strain')
            ->disableOriginalConstructor()
            ->getMock();
        $this->seo->setStrain($strain);
        $this->assertSame($strain, $this->seo->getStrain());

        $species = $this->getMockBuilder('AppBundle\Entity\Species')
            ->disableOriginalConstructor()
            ->getMock();
        $this->seo->setSpecies($species);
        $this->assertSame($species, $this->seo->getSpecies());
    }
}
