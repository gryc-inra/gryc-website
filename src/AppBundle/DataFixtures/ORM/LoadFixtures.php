<?php
// src/AppBundle/DataFixtures/ORM/LoadFixtures.php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ContactUsCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Load Fixtures.
 * Just fill the database with some data for development.
 */
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
        $userManager = $this->container->get('fos_user.user_manager');

        // Create a user Mathieu
        $user = $userManager->createUser();
        $user->setEmail('mathieu.piot@agroparistech.fr');
        $user->setUsername('Mathieu');
        $user->setPlainPassword('mathieu');
        $user->setFirstName('Mathieu');
        $user->setLastName('Piot');
        $user->setCompany('INRA');
        $user->setEnabled(true);
        $user->addRole('ROLE_SUPER_ADMIN');

        // Create a user Hugo
        $user2 = $userManager->createUser();
        $user2->setEmail('hugo.devillers@grignon.inra.fr');
        $user2->setUsername('Hugo');
        $user2->setPlainPassword('hugo');
        $user2->setFirstName('Hugo');
        $user2->setLastName('Devillers');
        $user2->setCompany('INRA');
        $user2->setEnabled(true);

        $manager->persist($user);
        $manager->persist($user2);

        $this->setReference('user-mathieu', $user);
        $this->setReference('user-hugo', $user2);

        //------------------------//
        //  ContactUs Categories  //
        //------------------------//

        // Définir les catégories dans un array
        $categoriesName = array('Account', 'Bug report', 'Informations request', 'Other (not listed)');

        // Pour chaque ligne, on crée un objet ContactUsCategory et on défini les attributs, puis on persiste
        foreach ($categoriesName as $categoryName) {
            $category = new ContactUsCategory();
            $category->setName($categoryName);

            $manager->persist($category);

            $this->setReference('category-'.$categoryName, $category);
        }

        // A la fin, on fait une unique requête pour tout persister en base
        $manager->flush();
    }
}
