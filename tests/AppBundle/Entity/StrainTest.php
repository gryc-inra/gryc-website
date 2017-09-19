<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Strain;
use PHPUnit\Framework\TestCase;

class StrainTest extends TestCase
{
    /**
     * @var Strain $object
     */
    private $object;

    public function setUp()
    {
        $this->object = new Strain();
    }

    public function tearDown()
    {
        $this->object = null;
    }

    public function testSetterAndGetter()
    {
        // Test the Id (by default, it's null)
        $this->assertNull($this->object->getId());

        // Test normal setters/getters
        $this->object->setName('name');
        $this->assertEquals('name', $this->object->getName());

        $this->object->setLength(100000);
        $this->assertEquals(100000, $this->object->getLength());

        $this->object->setGc(0.48297213622291);
        $this->assertEquals(0.48297213622291, $this->object->getGc());

        $this->object->setStatus('complete');
        $this->assertEquals('complete', $this->object->getStatus());

        $this->object->setCdsCount(12356);
        $this->assertEquals(12356, $this->object->getCdsCount());

        $this->object->setSlug('/mon/slug');
        $this->assertEquals('/mon/slug', $this->object->getSlug());

        $this->object->setPublic(true);
        $this->assertEquals(true, $this->object->getPublic());
        $this->assertEquals(true, $this->object->isPublic());
        $this->assertEquals(false, $this->object->isPrivate());
        $this->assertEquals('yes', $this->object->isPublicToString());
        $this->assertEquals('no', $this->object->isPrivateToString());

        $this->object->setPublic(false);
        $this->assertEquals(false, $this->object->getPublic());
        $this->assertEquals(false, $this->object->isPublic());
        $this->assertEquals(true, $this->object->isPrivate());
        $this->assertEquals('no', $this->object->isPublicToString());
        $this->assertEquals('yes', $this->object->isPrivateToString());

        $this->object->setTypeStrain(true);
        $this->assertEquals(true, $this->object->getTypeStrain());
        $this->assertEquals(true, $this->object->isTypeStrain());
        $this->assertEquals('yes', $this->object->isTypeStrainToString());

        $this->object->setTypeStrain(false);
        $this->assertEquals(false, $this->object->getTypeStrain());
        $this->assertEquals(false, $this->object->isTypeStrain());
        $this->assertEquals('no', $this->object->isTypeStrainToString());

        // Test add/remove/set/get/empty for Synonym array
        $this->object->addSynonym('synonym');
        $this->assertEquals(['synonym'], $this->object->getSynonymes());

        $this->object->removeSynonym('synonym');
        $this->assertEquals([], $this->object->getSynonymes());

        $synonymes = ['synonym', 'lsynonym2'];
        $this->object->setSynonymes($synonymes);
        $this->assertEquals($synonymes, $this->object->getSynonymes());

        $this->object->emptySynonymes();
        $this->assertEquals([], $this->object->getSynonymes());

        // Test Add/Remove/Get Chromosome
        $this->assertEquals('Doctrine\Common\Collections\ArrayCollection', get_class($this->object->getChromosomes()));

        $chromosome = $this->getMockBuilder('AppBundle\Entity\Chromosome')
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->addChromosome($chromosome);
        $this->assertEquals($chromosome, $this->object->getChromosomes()->first());

        $this->object->removeChromosome($chromosome);
        $this->assertEquals(true, $this->object->getChromosomes()->isEmpty());

        // Test Add/Remove/Get Seo
        $this->assertEquals('Doctrine\Common\Collections\ArrayCollection', get_class($this->object->getSeos()));

        $seo = $this->getMockBuilder('AppBundle\Entity\Seo')
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->addSeo($seo);
        $this->assertEquals($seo, $this->object->getSeos()->first());

        $this->object->removeSeo($seo);
        $this->assertEquals(true, $this->object->getSeos()->isEmpty());

        // Test Add/Remove/Get AuthorizedUser
        // and getAuthorizedUsersId
        // and isAuthorizedUser
        $this->assertEquals('Doctrine\Common\Collections\ArrayCollection', get_class($this->object->getAuthorizedUsers()));

        $user = $this->getMockBuilder('AppBundle\Entity\User')
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();
        $user
            ->method('getId')
            ->willReturn(1);

        $user2 = $this->getMockBuilder('AppBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object->addAuthorizedUser($user);
        $this->assertEquals($user, $this->object->getAuthorizedUsers()->first());

        $this->assertEquals([1], $this->object->getAuthorizedUsersId());
        $this->assertEquals(true, $this->object->isAuthorizedUser($user));
        $this->assertEquals(false, $this->object->isAuthorizedUser($user2));

        $this->object->removeAuthorizedUser($user);
        $this->assertEquals(true, $this->object->getAuthorizedUsers()->isEmpty());

        // Test Add/Get Species
        $species = $this->getMockBuilder('AppBundle\Entity\Species')
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->setSpecies($species);
        $this->assertEquals($species, $this->object->getSpecies());
    }
}
