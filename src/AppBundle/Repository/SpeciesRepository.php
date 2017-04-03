<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class SpeciesRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAvailableSpeciesAndStrains(User $user) {
        $query = $this->createQueryBuilder('species')
            ->leftJoin('species.strains', 'strains')
                ->addSelect('strains')
            ->leftJoin('strains.authorizedUsers', 'authorizedUsers')
                ->addSelect('authorizedUsers')
            ->leftJoin('species.seos', 'seos')
                ->addSelect('seos')
            ->where('strains.public = true')
            ->orWhere('authorizedUsers = :user')
                ->setParameter('user', $user)
            ->orderBy('species.scientificName', 'ASC')
            ->addOrderBy('strains.name', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function getAllSpeciesAndStrains() {
        $query = $this->createQueryBuilder('species')
            ->leftJoin('species.strains', 'strains')
            ->addSelect('strains')
            ->leftJoin('species.seos', 'seos')
            ->addSelect('seos')
            ->orderBy('species.scientificName', 'ASC')
            ->orderBy('strains.name', 'ASC');

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
