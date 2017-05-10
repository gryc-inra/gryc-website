<?php

namespace AppBundle\SearchRepository;

use AppBundle\Entity\User;
use FOS\ElasticaBundle\Repository;

class UserRepository extends Repository
{
    public function searchByNameQuery($q, $p)
    {
        $query = new \Elastica\Query();

        if (null !== $q) {
            $queryString = new \Elastica\Query\QueryString();
            $queryString->setFields(['fullName^2', 'fullName.ngramed', 'email^2', 'email.ngramed']);
            $queryString->setDefaultOperator('AND');
            $queryString->setQuery($q);

            $query->setQuery($queryString);
        } else {
            $matchAllQuery = new \Elastica\Query\MatchAll();

            $query->setQuery($matchAllQuery);
            $query->setSort(['fullName' => 'asc']);
        }

        $query
            ->setFrom(($p - 1) * User::NUM_ITEMS)
            ->setSize(User::NUM_ITEMS);

        // build $query with Elastica objects
        return $query;
    }
}
