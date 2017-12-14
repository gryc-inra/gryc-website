<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Strain;
use PHPUnit\Framework\TestCase;

class StrainTest extends TestCase
{
    /**
     * @var Strain
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
        $this->assertSame('name', $this->object->getName());

        $this->object->setLength(100000);
        $this->assertSame(100000, $this->object->getLength());

        $this->object->setGc(0.48297213622291);
        $this->assertSame(0.48297213622291, $this->object->getGc());

        $this->object->setStatus('complete');
        $this->assertSame('complete', $this->object->getStatus());

        $this->object->setCdsCount(12356);
        $this->assertSame(12356, $this->object->getCdsCount());

        $this->object->setSlug('/mon/slug');
        $this->assertSame('/mon/slug', $this->object->getSlug());

        $this->object->setPublic(true);
        $this->assertTrue($this->object->getPublic());
        $this->assertTrue($this->object->isPublic());
        $this->assertFalse($this->object->isPrivate());
        $this->assertSame('yes', $this->object->isPublicToString());
        $this->assertSame('no', $this->object->isPrivateToString());

        $this->object->setPublic(false);
        $this->assertFalse($this->object->getPublic());
        $this->assertFalse($this->object->isPublic());
        $this->assertTrue($this->object->isPrivate());
        $this->assertSame('no', $this->object->isPublicToString());
        $this->assertSame('yes', $this->object->isPrivateToString());

        $this->object->setTypeStrain(true);
        $this->assertTrue($this->object->getTypeStrain());
        $this->assertTrue($this->object->isTypeStrain());
        $this->assertSame('yes', $this->object->isTypeStrainToString());

        $this->object->setTypeStrain(false);
        $this->assertFalse($this->object->getTypeStrain());
        $this->assertFalse($this->object->isTypeStrain());
        $this->assertSame('no', $this->object->isTypeStrainToString());

        // Test add/remove/set/get/empty for Synonym array
        $this->object->addSynonym('synonym');
        $this->assertSame(['synonym'], $this->object->getSynonymes());

        $this->object->removeSynonym('synonym');
        $this->assertSame([], $this->object->getSynonymes());

        $synonymes = ['synonym', 'lsynonym2'];
        $this->object->setSynonymes($synonymes);
//        $this->assertSame($synonymes, $this->object->getSynonymes());

        $this->object->emptySynonymes();
        $this->assertSame([], $this->object->getSynonymes());

        // Test Add/Remove/Get Chromosome
        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->object->getChromosomes()));

        $chromosome = $this->getMockBuilder('AppBundle\Entity\Chromosome')
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->addChromosome($chromosome);
        $this->assertSame($chromosome, $this->object->getChromosomes()->first());

        $this->object->removeChromosome($chromosome);
        $this->assertTrue($this->object->getChromosomes()->isEmpty());

        // Test Add/Remove/Get Seo
        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->object->getSeos()));

        $seo = $this->getMockBuilder('AppBundle\Entity\Seo')
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->addSeo($seo);
        $this->assertSame($seo, $this->object->getSeos()->first());

        $this->object->removeSeo($seo);
        $this->assertTrue($this->object->getSeos()->isEmpty());

        // Test Add/Remove/Get AuthorizedUser
        // and getAuthorizedUsersId
        // and isAuthorizedUser
        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->object->getUsers()));

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

        $this->object->addUser($user);
        $this->assertSame($user, $this->object->getUsers()->first());

        $this->assertSame([1], $this->object->getUsersId());
        $this->assertTrue($this->object->isAllowedUser($user));
        $this->assertFalse($this->object->isAllowedUser($user2));

        $this->object->removeUser($user);
        $this->assertTrue($this->object->getUsers()->isEmpty());

        // Test Add/Get Species
        $species = $this->getMockBuilder('AppBundle\Entity\Species')
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->setSpecies($species);
        $this->assertSame($species, $this->object->getSpecies());
    }
}
