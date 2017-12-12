<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Dbxref;
use PHPUnit\Framework\TestCase;

class DbxrefTest extends TestCase
{
    /**
     * @var Dbxref
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
        $this->assertSame('name', $this->dbxref->getName());

        $this->dbxref->setDescription('description');
        $this->assertSame('description', $this->dbxref->getDescription());

        $this->dbxref->setPattern('Pattern');
        $this->assertSame('Pattern', $this->dbxref->getPattern());

        $this->dbxref->setUrl('http://www.site.tld');
        $this->assertSame('http://www.site.tld', $this->dbxref->getUrl());

        $this->dbxref->setSource('source');
        $this->assertSame('source', $this->dbxref->getSource());
    }
}
