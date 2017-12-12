<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\DnaSequence;
use PHPUnit\Framework\TestCase;

class DnaSequenceTest extends TestCase
{
    /**
     * @var DnaSequence
     */
    private $dnaSequence;

    public function setUp()
    {
        $this->dnaSequence = new DnaSequence();
    }

    public function tearDown()
    {
        $this->dnaSequence = null;
    }

    public function testSetterAndGetter()
    {
        $this->assertNull($this->dnaSequence->getId());

        $this->dnaSequence->setLetterCount(10000);
        $this->assertSame(10000, $this->dnaSequence->getLetterCount());

        $this->dnaSequence->setDna('AAAAAAAAAAAAAA');
        $this->assertSame('AAAAAAAAAAAAAA', $this->dnaSequence->getDna());
    }
}
