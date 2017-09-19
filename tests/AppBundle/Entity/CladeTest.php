<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Clade;
use PHPUnit\Framework\TestCase;

class CladeTest extends TestCase
{
    /**
     * @var Clade $clade
     */
    private $clade;

    public function setUp()
    {
        $this->clade = new Clade();
    }

    public function tearDown()
    {
        $this->clade = null;
    }

    public function testSetterAndGetter()
    {
        $this->assertNull($this->clade->getId());

        $this->clade->setName('name');
        $this->assertEquals('name', $this->clade->getName());

        $this->clade->setDescription('description');
        $this->assertEquals('description', $this->clade->getDescription());

        $this->clade->setMainClade(true);
        $this->assertEquals(true, $this->clade->getMainCLade());

        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->clade->getSpecies()));
    }

    /**
     * @dataProvider mainCladeData
     */
    public function testIsMainCladeToString($isMainCladeBool, $isMainCladeString)
    {
        $this->clade->setMainClade($isMainCladeBool);

        $this->assertSame($isMainCladeString, $this->clade->isMainCladeToString());
    }

    public function mainCladeData()
    {
        return [
            [true, 'Yes'],
            [false, 'No']
        ];
    }
}
