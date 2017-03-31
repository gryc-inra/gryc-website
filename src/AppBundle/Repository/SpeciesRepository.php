<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class SpeciesRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllSpeciesWithAvailableStrains(User $user = null, AuthorizationChecker $authorizationChecker = null)
    {
        // At start, we recove all the species and all the strains (ADMIN rights)
        $query = $this->createQueryBuilder('species')
            ->leftJoin('species.strains', 'strains')
                ->addSelect('strains')
            ->leftJoin('species.seos', 'seos')
                ->addSelect('seos')
            ->orderBy('species.scientificName', 'ASC')
            ->orderBy('strains.name', 'ASC');

        if (null !== $authorizationChecker) {
            // If the user is connected and isn't an administrator
            if ($authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $query
                    ->leftJoin('strains.authorizedUsers', 'authorizedUsers')
                    ->addSelect('authorizedUsers')
                    ->where('strains.public = true')
                    ->orWhere('authorizedUsers = :user')
                    ->setParameter('user', $user);
            }

            // If the user is not connected
            elseif ($authorizationChecker->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
                $query
                    ->where('strains.public = true');
            }
        }

        return $query->getQuery()->getResult();
    }

    public function getOneSpeciesWithStrains($slug, User $user = null)
    {
        $query = $this->createQueryBuilder('species')
            ->where('species.slug = :slug')
                ->setParameter('slug', $slug)
            ->leftJoin('species.strains', 'strains')
                ->addSelect('strains')
            ->leftJoin('species.seos', 'seos')
                ->addSelect('seos');

        return $query->getQuery()->getOneOrNullResult();
    }

    public function findAllWithSeo()
    {
        $query = $this
            ->createQueryBuilder('species')
                ->leftJoin('species.strains', 'strains')
                    ->addSelect('strains')
                ->leftJoin('species.seos', 'species_seos')
                    ->addSelect('species_seos')
                ->leftJoin('strains.seos', 'strains_seos')
                    ->addSelect('strains_seos')
            ->getQuery();

        return $query->getResult();
    }
}
