<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Chromosome;
use PHPUnit\Framework\TestCase;

class ChromosomeTest extends TestCase
{
    /**
     * @var Chromosome
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
        $this->assertSame('name', $this->chromosome->getName());

        $this->chromosome->addAccession('accession');
        $this->assertSame(['accession'], $this->chromosome->getAccessions());

        $this->chromosome->removeAccession('accession');
        $this->assertSame([], $this->chromosome->getAccessions());

        $accessions = ['accession', 'accession2'];
        $this->chromosome->setAccession($accessions);
        $this->assertSame($accessions, $this->chromosome->getAccessions());

        $this->chromosome->emptyAccessions();
        $this->assertSame([], $this->chromosome->getAccessions());

        $this->chromosome->setDescription('description');
        $this->assertSame('description', $this->chromosome->getDescription());

        $this->chromosome->setProjectId('project_number_1');
        $this->assertSame('project_number_1', $this->chromosome->getProjectId());

        $this->chromosome->addKeyword('keyword');
        $this->assertSame(['keyword'], $this->chromosome->getKeywords());

        $this->chromosome->removeKeyword('keyword');
        $this->assertSame([], $this->chromosome->getKeywords());

        $keywords = ['keyword', 'keyword2'];
        $this->chromosome->setKeywords($keywords);
        $this->assertSame($keywords, $this->chromosome->getKeywords());

        $this->chromosome->emptyKeywords();
        $this->assertSame([], $this->chromosome->getKeywords());

        $datetime = new \DateTime();
        $this->chromosome->setDateCreated($datetime);
        $this->assertSame($datetime, $this->chromosome->getDateCreated());

        $this->chromosome->setNumCreated(1);
        $this->assertSame(1, $this->chromosome->getNumCreated());

        $this->chromosome->setDateReleased($datetime);
        $this->assertSame($datetime, $this->chromosome->getDateReleased());

        $this->chromosome->setNumReleased(1);
        $this->assertSame(1, $this->chromosome->getNumReleased());

        $this->chromosome->setNumVersion(1);
        $this->assertSame(1, $this->chromosome->getNumVersion());

        $this->chromosome->setLength(1000);
        $this->assertSame(1000, $this->chromosome->getLength());

        $this->chromosome->setGc(0.48297213622291);
        $this->assertSame(0.48297213622291, $this->chromosome->getGc());

        $this->chromosome->setCdsCount(12356);
        $this->assertSame(12356, $this->chromosome->getCdsCount());

        $this->assertNull($this->chromosome->getMitochondrial());

        $this->chromosome->setMitochondrial(true);
        $this->assertTrue($this->chromosome->getMitochondrial());

        $this->chromosome->setMitochondrial(false);
        $this->assertFalse($this->chromosome->getMitochondrial());

        $this->chromosome->setComment('comment');
        $this->assertSame('comment', $this->chromosome->getComment());

        $strain = $this->getMockBuilder('AppBundle\Entity\Strain')
            ->disableOriginalConstructor()
            ->getMock();
        $this->chromosome->setStrain($strain);
        $this->assertSame($strain, $this->chromosome->getStrain());

        $dnaSequence = $this->getMockBuilder('AppBundle\Entity\DnaSequence')
            ->disableOriginalConstructor()
            ->getMock();
        $this->chromosome->setDnaSequence($dnaSequence);
        $this->assertSame($dnaSequence, $this->chromosome->getDnaSequence());

        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->chromosome->getFlatFiles()));

        $flatFile = $this->getMockBuilder('AppBundle\Entity\FlatFile')
            ->disableOriginalConstructor()
            ->getMock();
        $this->chromosome->addFlatFile($flatFile);
        $this->assertSame($flatFile, $this->chromosome->getFlatFiles()->first());

        $this->chromosome->removeFlatFile($flatFile);
        $this->assertTrue($this->chromosome->getFlatFiles()->isEmpty());

        $this->chromosome->setSlug('/mon/slug');
        $this->assertSame('/mon/slug', $this->chromosome->getSlug());

        $this->chromosome->setSource('MY_STRAIN//ABC0S02.embl');
        $this->assertSame('MY_STRAIN//ABC0S02.embl', $this->chromosome->getSource());

        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->chromosome->getLocus()));

        $locus = $this->getMockBuilder('AppBundle\Entity\Locus')
            ->disableOriginalConstructor()
            ->getMock();
        $this->chromosome->addLocus($locus);
        $this->assertSame($locus, $this->chromosome->getLocus()->first());

        $this->chromosome->removeLocus($locus);
        $this->assertTrue($this->chromosome->getLocus()->isEmpty());
    }
}
