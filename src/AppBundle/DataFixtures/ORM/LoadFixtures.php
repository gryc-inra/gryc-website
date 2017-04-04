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
        $user->setEmail('mathieu.piot@inra.fr');
        $user->setPlainPassword('mathieu');
        $user->setFirstName('Mathieu');
        $user->setLastName('Piot');
        $user->setCompany('INRA');
        $user->setIsActive(true);
        $user->addRole('ROLE_SUPER_ADMIN');

        // Create a user Hugo
        $user2 = $userManager->createUser();
        $user2->setEmail('hugo.devillers@inra.fr');
        $user2->setPlainPassword('hugo');
        $user2->setFirstName('Hugo');
        $user2->setLastName('Devillers');
        $user2->setCompany('INRA');
        $user2->setIsActive(true);

        $manager->persist($user);
        $manager->persist($user2);

        $this->setReference('user-mathieu', $user);
        $this->setReference('user-hugo', $user2);

        // Flush all
        $manager->flush();
    }
}
