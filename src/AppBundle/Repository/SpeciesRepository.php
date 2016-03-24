<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

class SpeciesRepository extends \Doctrine\ORM\EntityRepository
{
    public function getSpeciesWithStrains($slug = null, User $user = null)
    {
        $query = $this
            ->createQueryBuilder('species')
            ->leftJoin('species.strains', 'strains')
                ->addSelect('strains')
        ;

        // Select all public strains
        $query->where('strains.public = true');

        // If a user is connected recover his strains
        if (null !== $user) {
            $query
                ->leftJoin('strains.authorizedUsers', 'users')
                ->addSelect('users')
                ->orWhere('users = :user')
                ->setParameter('user', $user)
            ;
        }

        // If we want one specific species
        if (null !== $slug) {
            $query
                ->andWhere('species.slug = :slug')
                ->setParameter('slug', $slug)
                ->leftJoin('species.seos', 'seos')
                    ->addSelect('seos')
            ;

            return $query->getQuery()->getOneOrNullResult();
        // If we want list species
        } else {
            // Order the results
            $query->orderBy('species.scientificName', 'ASC');

            // Recover resultS
            return $query->getQuery()->getResult();
        }
    }
}
