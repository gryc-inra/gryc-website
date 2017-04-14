<?php

namespace AppBundle\SearchRepository;

use AppBundle\Entity\User;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MultiMatch;
use Elastica\Query\Term;

class GlobalRepository
{
    public function searchQuery($keyword = null, User $user)
    {
        // Create a bool query
        $query = new BoolQuery();
        $query->setMinimumShouldMatch(1);

        // Search in note
        $noteQuery = new MultiMatch();
        $noteQuery->setFields(['note^2', 'note.stemmed']);
        $noteQuery->setFuzziness('AUTO');
        $noteQuery->setQuery($keyword);
        $query->addShould($noteQuery);

        // Search in name
        $nameQuery = new MultiMatch();
        $nameQuery->setFields(['name^2', 'name.ngramed']);
        $nameQuery->setQuery($keyword);
        $query->addShould($nameQuery);

        // Search in gene
        $geneQuery = new Match();
        $geneQuery->setFieldQuery('annotation.gene', $keyword);
        $query->addShould($geneQuery);

        // Create a BoolQuery with filters
        $boolFilter = new BoolQuery();
        $boolFilter->setMinimumShouldMatch(1);
        $query->addFilter($boolFilter);

        // Set a user filter
        $userFilter = new Term();
        $userFilter->setTerm('authorized_users_id', $user->getId());
        $boolFilter->addShould($userFilter);

        // Set a public filter
        $publicFilter = new Term();
        $publicFilter->setTerm('public', true);
        $boolFilter->addShould($publicFilter);

        return $query;
    }
}
