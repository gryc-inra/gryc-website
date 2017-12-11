<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Species;
use PHPUnit\Framework\TestCase;

class SpeciesTest extends TestCase
{
    /**
     * @var Species
     */
    private $species;

    public function setUp()
    {
        $this->species = new Species();
    }

    public function tearDown()
    {
        $this->species = null;
    }

    public function testSetterAndGetter()
    {
        // Test the Id (by default, it's null)
        $this->assertNull($this->species->getId());

        // Test normal setters/getters
        $clade = $this->getMockBuilder('AppBundle\Entity\Clade')
            ->disableOriginalConstructor()
            ->getMock();
        $this->species->setClade($clade);
        $this->assertEquals($clade, $this->species->getClade());

        $this->species->setScientificName('Genus species');
        $this->assertEquals('Genus species', $this->species->getScientificName());

        $this->species->setSpecies('species');
        $this->assertEquals('species', $this->species->getSpecies());

        $this->species->setGenus('genus');
        $this->assertEquals('genus', $this->species->getGenus());

        // Test add/remove/set/get/empty for Lineage array
        $this->species->addLineage('lineage');
        $this->assertEquals(['lineage'], $this->species->getLineages());

        $this->species->removeLineage('lineage');
        $this->assertEquals([], $this->species->getLineages());

        $lineages = ['lineage', 'lineage2'];
        $this->species->setLineages($lineages);
        $this->assertEquals($lineages, $this->species->getLineages());

        $this->species->emptyLineages();
        $this->assertEquals([], $this->species->getLineages());

        // Continue normal setters/getters
        $this->species->setTaxid(123456);
        $this->assertEquals(123456, $this->species->getTaxid());

        $this->species->setGeneticCode(1);
        $this->assertEquals(1, $this->species->getGeneticCode());

        $this->species->setMitoCode(4);
        $this->assertEquals(4, $this->species->getMitoCode());

        // Test add/remove/set/get/empty for Synonym array
        $this->species->addSynonym('synonym');
        $this->assertEquals(['synonym'], $this->species->getSynonyms());

        $this->species->removeSynonym('synonym');
        $this->assertEquals([], $this->species->getSynonyms());

        $synonymes = ['synonym', 'lsynonym2'];
        $this->species->setSynonyms($synonymes);
        $this->assertEquals($synonymes, $this->species->getSynonyms());

        $this->species->emptySynonyms();
        $this->assertEquals([], $this->species->getSynonyms());

        // Continue normal setters/getters
        $this->species->setDescription('description');
        $this->assertEquals('description', $this->species->getDescription());

        // Test Add/Remove/Get Strain
        $this->assertEquals('Doctrine\Common\Collections\ArrayCollection', get_class($this->species->getStrains()));

        $strain = $this->getMockBuilder('AppBundle\Entity\Strain')
            ->disableOriginalConstructor()
            ->getMock();
        $this->species->addStrain($strain);
        $this->assertEquals($strain, $this->species->getStrains()->first());

        $this->species->removeStrain($strain);
        $this->assertEquals(true, $this->species->getStrains()->isEmpty());

        // Test Add/Remove/Get Seo
        $this->assertEquals('Doctrine\Common\Collections\ArrayCollection', get_class($this->species->getSeos()));

        $seo = $this->getMockBuilder('AppBundle\Entity\Seo')
            ->disableOriginalConstructor()
            ->getMock();
        $this->species->addSeo($seo);
        $this->assertEquals($seo, $this->species->getSeos()->first());

        $this->species->removeSeo($seo);
        $this->assertEquals(true, $this->species->getSeos()->isEmpty());

        // Continue with Slug
        $this->species->setSlug('/mon/slug');
        $this->assertEquals('/mon/slug', $this->species->getSlug());
    }
}
