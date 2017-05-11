<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadFixtures extends AbstractFixture implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        //---------//
        //  Users  //
        //---------//
        $userManager = $this->container->get('app.user_manager');

        // Create a user Mathieu
        $user = $userManager->createUser();
        $user->setEmail('user1@domain.tld');
        $user->setPlainPassword('User1-78');
        $user->setFirstName('User');
        $user->setLastName('1');
        $user->setCompany('The 1 company');
        $user->setIsActive(true);
        $user->addRole('ROLE_SUPER_ADMIN');

        // Create a user Hugo
        $user2 = $userManager->createUser();
        $user2->setEmail('user2@domain.tld');
        $user2->setPlainPassword('User2-78');
        $user2->setFirstName('User');
        $user2->setLastName('2');
        $user2->setCompany('The 2 company');
        $user2->setIsActive(true);

        $manager->persist($user);
        $manager->persist($user2);

        $this->setReference('user-user1', $user);
        $this->setReference('user-user2', $user2);

        // Flush all
        $manager->flush();
    }
}
