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
        $this->assertSame('my.name-email@domain.tld', $this->object->getEmail());

        $this->assertSame('my.name-email@domain.tld', $this->object->getUsername());

        $this->object->setPlainPassword('plainPassword.');
        $this->assertSame('plainPassword.', $this->object->getPlainPassword());

        $this->object->setPassword('password.');
        $this->assertSame('password.', $this->object->getPassword());

        $this->assertNull($this->object->getSalt());

        $this->object->setEnabled(true);
        $this->assertTrue($this->object->isEnabled());

        $this->object->setConfirmationToken('token');
        $this->assertSame('token', $this->object->getConfirmationToken());

        $this->object->eraseCredentials();
        $this->assertNull($this->object->getPlainPassword());

        $this->assertTrue($this->object->isAccountNonExpired());
        $this->assertTrue($this->object->isAccountNonLocked());
        $this->assertTrue($this->object->isCredentialsNonExpired());
        $this->assertTrue($this->object->isEnabled());

        $this->object->setFirstName('FirstName');
        $this->assertSame('FirstName', $this->object->getFirstName());

        $this->object->setLastName('LastName');
        $this->assertSame('LastName', $this->object->getLastName());

        $this->assertSame('FirstName LastName', $this->object->getFullName());

        $this->object->setCompany('company');
        $this->assertSame('company', $this->object->getCompany());

        $this->object->setSessionId('5dsf4e54ge4sfdsfds46g4ds6fs');
        $this->assertSame('5dsf4e54ge4sfdsfds46g4ds6fs', $this->object->getSessionId());

        // Test Add/Remove/Get AuthorizedStrain
        $this->assertSame('Doctrine\Common\Collections\ArrayCollection', get_class($this->object->getStrains()));

        $strain = $this->getMockBuilder('AppBundle\Entity\Strain')
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->addStrain($strain);
        $this->assertSame($strain, $this->object->getStrains()->first());

        $this->object->removeStrain($strain);
        $this->assertTrue($this->object->getStrains()->isEmpty());

        // Test add/remove/set/get for role array
        $this->object->addRole(User::ROLE_DEFAULT);
        $this->assertSame([User::ROLE_DEFAULT], $this->object->getRoles());

        $this->object->addRole('role');
        $this->assertSame(['ROLE', User::ROLE_DEFAULT], $this->object->getRoles());

        $this->object->removeRole('ROLE');
        $this->assertSame([User::ROLE_DEFAULT], $this->object->getRoles());

        $roles = ['ROLE1', 'role2', User::ROLE_DEFAULT];
        $this->object->setRoles($roles);
        $this->assertSame($roles, $this->object->getRoles());
    }
}
