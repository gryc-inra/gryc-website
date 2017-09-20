<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $object;

    public function setUp()
    {
        $this->object = new User();
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
        $this->object->setEmail('my.name-email@domain.tld');
        $this->assertEquals('my.name-email@domain.tld', $this->object->getEmail());

        $this->assertEquals('my.name-email@domain.tld', $this->object->getUsername());

        $this->object->setPlainPassword('plainPassword.');
        $this->assertEquals('plainPassword.', $this->object->getPlainPassword());

        $this->object->setPassword('password.');
        $this->assertEquals('password.', $this->object->getPassword());

        $this->assertNull($this->object->getSalt());

        $this->object->setIsActive(true);
        $this->assertEquals(true, $this->object->getIsActive());

        $this->object->setConfirmationToken('token');
        $this->assertEquals('token', $this->object->getConfirmationToken());

        $this->object->eraseCredentials();
        $this->assertNull($this->object->getPlainPassword());

        $this->assertEquals(true, $this->object->isAccountNonExpired());
        $this->assertEquals(true, $this->object->isAccountNonLocked());
        $this->assertEquals(true, $this->object->isCredentialsNonExpired());
        $this->assertEquals(true, $this->object->isEnabled());

        $this->object->setFirstName('FirstName');
        $this->assertEquals('FirstName', $this->object->getFirstName());

        $this->object->setLastName('LastName');
        $this->assertEquals('LastName', $this->object->getLastName());

        $this->assertEquals('FirstName LastName', $this->object->getFullName());

        $this->object->setCompany('company');
        $this->assertEquals('company', $this->object->getCompany());

        $this->object->setSessionId('5dsf4e54ge4sfdsfds46g4ds6fs');
        $this->assertEquals('5dsf4e54ge4sfdsfds46g4ds6fs', $this->object->getSessionId());

        // Test Add/Remove/Get AuthorizedStrain
        $this->assertEquals('Doctrine\Common\Collections\ArrayCollection', get_class($this->object->getAuthorizedStrains()));

        $strain = $this->getMockBuilder('AppBundle\Entity\Strain')
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->addAuthorizedStrain($strain);
        $this->assertEquals($strain, $this->object->getAuthorizedStrains()->first());

        $this->object->removeAuthorizedStrain($strain);
        $this->assertEquals(true, $this->object->getAuthorizedStrains()->isEmpty());

        // Test add/remove/set/get for role array
        $this->object->addRole(User::ROLE_DEFAULT);
        $this->assertEquals([User::ROLE_DEFAULT], $this->object->getRoles());

        $this->object->addRole('role');
        $this->assertEquals(['ROLE', User::ROLE_DEFAULT], $this->object->getRoles());

        $this->object->removeRole('ROLE');
        $this->assertEquals([User::ROLE_DEFAULT], $this->object->getRoles());

        $roles = ['ROLE1', 'role2', User::ROLE_DEFAULT];
        $this->object->setRoles($roles);
        $this->assertEquals($roles, $this->object->getRoles());
    }
}
