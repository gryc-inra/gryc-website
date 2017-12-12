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
        $this->assertSame($clade, $this->species->getClade());

        $this->species->setScientificName('Genus species');
        $this->assertSame('Genus species', $this->species->getScientificName());

        $this->species->setSpecies('species');
        $this->assertSame('species', $this->species->getSpecies());

        $this->species->setGenus('genus');
        $this->assertSame('genus', $this->species->getGenus());

        // Test add/remove/set/get/empty for Lineage array
        $this->species->addLineage('lineage');
        $this->assertSame(['lineage'], $this->species->getLineages());

        $this->species->removeLineage('lineage');
        $this->assertSame([], $this->species->getLineages());

        $lineages = ['lineage', 'lineage2'];
        $this->species->setLineages($lineages);
        $this->assertSame($lineages, $this->species->getLineages());

        $this->species->emptyLineages();
        $this->assertSame([], $this->species->getLineages());

        // Continue normal setters/getters
        $this->species->setTaxid(123456);
        $this->assertSame(123456, $this->species->getTaxid());

        $this->species->setGeneticCode(1);
        $this->assertSame(1, $this->species->getGeneticCode());

        $this->species->setMitoCode(4);
        $this->assertSame(4, $this->species->getMitoCode());

        // Test add/remove/set/get/empty for Synonym array
        $this->species->addSynonym('synonym');
        $this->assertSame(['synonym'], $this->species->getSynonyms());

        $this->species->removeSynonym('synonym');
        $this->assertSame([], $this->species->getSynonyms());

        $synonymes = ['synonym', 'lsynonym2'];
        $this->species->setSynonyms($synonymes);
        $this->assertSame($synonymes, $this->species->getSynonyms());

        $this->species->emptySynonyms();
        $this->assertSame([], $this->species->getSynonyms());

        // Continue normal setters/getters
        $this->species->setDescription('description');
        $this->assertSame('description', $this->species->getDescription());

        // Test Add/Remove/Get Strain
        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->species->getStrains()));

        $strain = $this->getMockBuilder('AppBundle\Entity\Strain')
            ->disableOriginalConstructor()
            ->getMock();
        $this->species->addStrain($strain);
        $this->assertSame($strain, $this->species->getStrains()->first());

        $this->species->removeStrain($strain);
        $this->assertTrue($this->species->getStrains()->isEmpty());

        // Test Add/Remove/Get Seo
        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->species->getSeos()));

        $seo = $this->getMockBuilder('AppBundle\Entity\Seo')
            ->disableOriginalConstructor()
            ->getMock();
        $this->species->addSeo($seo);
        $this->assertSame($seo, $this->species->getSeos()->first());

        $this->species->removeSeo($seo);
        $this->assertTrue($this->species->getSeos()->isEmpty());

        // Continue with Slug
        $this->species->setSlug('/mon/slug');
        $this->assertSame('/mon/slug', $this->species->getSlug());
    }
}
