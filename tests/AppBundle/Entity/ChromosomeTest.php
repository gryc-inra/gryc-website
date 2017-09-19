<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Chromosome;
use PHPUnit\Framework\TestCase;

class ChromosomeTest extends TestCase
{
    /**
     * @var Chromosome $chromosome
     */
    private $chromosome;

    public function setUp()
    {
        $this->chromosome = new Chromosome();
    }

    public function tearDown()
    {
        $this->chromosome = null;
    }

    public function testSetterAndGetter()
    {
        $this->assertNull($this->chromosome->getId());

        $this->chromosome->setName('name');
        $this->assertEquals('name', $this->chromosome->getName());

        $this->chromosome->addAccession('accession');
        $this->assertEquals(['accession'], $this->chromosome->getAccessions());

        $this->chromosome->removeAccession('accession');
        $this->assertEquals([], $this->chromosome->getAccessions());

        $accessions = ['accession', 'accession2'];
        $this->chromosome->setAccession($accessions);
        $this->assertEquals($accessions, $this->chromosome->getAccessions());

        $this->chromosome->emptyAccessions();
        $this->assertEquals([], $this->chromosome->getAccessions());

        $this->chromosome->setDescription('description');
        $this->assertEquals('description', $this->chromosome->getDescription());

        $this->chromosome->setProjectId('project_number_1');
        $this->assertEquals('project_number_1', $this->chromosome->getProjectId());

        $this->chromosome->addKeyword('keyword');
        $this->assertEquals(['keyword'], $this->chromosome->getKeywords());

        $this->chromosome->removeKeyword('keyword');
        $this->assertEquals([], $this->chromosome->getKeywords());

        $keywords = ['keyword', 'keyword2'];
        $this->chromosome->setKeywords($keywords);
        $this->assertEquals($keywords, $this->chromosome->getKeywords());

        $this->chromosome->emptyKeywords();
        $this->assertEquals([], $this->chromosome->getKeywords());

        $datetime = new \DateTime();
        $this->chromosome->setDateCreated($datetime);
        $this->assertEquals($datetime, $this->chromosome->getDateCreated());

        $this->chromosome->setNumCreated(1);
        $this->assertEquals(1, $this->chromosome->getNumCreated());

        $this->chromosome->setDateReleased($datetime);
        $this->assertEquals($datetime, $this->chromosome->getDateReleased());

        $this->chromosome->setNumReleased(1);
        $this->assertEquals(1, $this->chromosome->getNumReleased());

        $this->chromosome->setNumVersion(1);
        $this->assertEquals(1, $this->chromosome->getNumVersion());

        $this->chromosome->setLength(1000);
        $this->assertEquals(1000, $this->chromosome->getLength());

        $this->chromosome->setGc(0.48297213622291);
        $this->assertEquals(0.48297213622291, $this->chromosome->getGc());

        $this->chromosome->setCdsCount(12356);
        $this->assertEquals(12356, $this->chromosome->getCdsCount());

        $this->assertNull($this->chromosome->getMitochondrial());

        $this->chromosome->setMitochondrial(true);
        $this->assertEquals(true, $this->chromosome->getMitochondrial());

        $this->chromosome->setMitochondrial(false);
        $this->assertEquals(false, $this->chromosome->getMitochondrial());

        $this->chromosome->setComment('comment');
        $this->assertEquals('comment', $this->chromosome->getComment());

        $strain = $this->getMockBuilder('AppBundle\Entity\Strain')
            ->disableOriginalConstructor()
            ->getMock();
        $this->chromosome->setStrain($strain);
        $this->assertEquals($strain, $this->chromosome->getStrain());

        $dnaSequence = $this->getMockBuilder('AppBundle\Entity\DnaSequence')
            ->disableOriginalConstructor()
            ->getMock();
        $this->chromosome->setDnaSequence($dnaSequence);
        $this->assertEquals($dnaSequence, $this->chromosome->getDnaSequence());

        $this->assertEquals('Doctrine\Common\Collections\ArrayCollection', get_class($this->chromosome->getFlatFiles()));

        $flatFile = $this->getMockBuilder('AppBundle\Entity\FlatFile')
            ->disableOriginalConstructor()
            ->getMock();
        $this->chromosome->addFlatFile($flatFile);
        $this->assertEquals($flatFile, $this->chromosome->getFlatFiles()->first());

        $this->chromosome->removeFlatFile($flatFile);
        $this->assertEquals(true, $this->chromosome->getFlatFiles()->isEmpty());

        $this->chromosome->setSlug('/mon/slug');
        $this->assertEquals('/mon/slug', $this->chromosome->getSlug());

        $this->chromosome->setSource('MY_STRAIN//ABC0S02.embl');
        $this->assertEquals('MY_STRAIN//ABC0S02.embl', $this->chromosome->getSource());

        $this->assertEquals('Doctrine\Common\Collections\ArrayCollection', get_class($this->chromosome->getLocus()));

        $locus = $this->getMockBuilder('AppBundle\Entity\Locus')
            ->disableOriginalConstructor()
            ->getMock();
        $this->chromosome->addLocus($locus);
        $this->assertEquals($locus, $this->chromosome->getLocus()->first());

        $this->chromosome->removeLocus($locus);
        $this->assertEquals(true, $this->chromosome->getLocus()->isEmpty());
    }
}
