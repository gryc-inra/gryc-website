<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Dbxref;
use PHPUnit\Framework\TestCase;

class DbxrefTest extends TestCase
{
    /**
     * @var Dbxref $dbxref
     */
    private $dbxref;

    public function setUp()
    {
        $this->dbxref = new Dbxref();
    }

    public function tearDown()
    {
        $this->dbxref = null;
    }

    public function testSetterAndGetter()
    {
        $this->assertNull($this->dbxref->getId());

        $this->dbxref->setName('name');
        $this->assertEquals('name', $this->dbxref->getName());

        $this->dbxref->setDescription('description');
        $this->assertEquals('description', $this->dbxref->getDescription());

        $this->dbxref->setPattern('Pattern');
        $this->assertEquals('Pattern', $this->dbxref->getPattern());

        $this->dbxref->setUrl('http://www.site.tld');
        $this->assertEquals('http://www.site.tld', $this->dbxref->getUrl());

        $this->dbxref->setSource('source');
        $this->assertEquals('source', $this->dbxref->getSource());
    }
}
