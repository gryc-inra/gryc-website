<?php

namespace AppBundle\SearchRepository;

use AppBundle\Entity\Project;
use AppBundle\Entity\Type;
use AppBundle\Entity\User;

class GlobalRepository
{
    public function searchQuery($keyword = null, User $user, $category = null)
    {
        // Create the search query
        $query = new \Elastica\Query\BoolQuery();

        //-------------------------------//
        // Set queries used in BoolQuery //
        //-------------------------------//

        $keywordQuery = new \Elastica\Query\QueryString();
        $keywordQuery->setFields(['name^2', 'annotation', 'note']);
        $keywordQuery->setDefaultOperator('AND');
        $keywordQuery->setQuery($keyword);

        //----------------------------------------//
        // Set security queries used in BoolQuery //
        //----------------------------------------//

        // Set a user filter
        $userSecureQuery = new \Elastica\Query\Term();
        $userSecureQuery->setTerm('authorized_users_id', $user->getId());

        //-------------------------------------------//
        // Assign previous queries to each BoolQuery //
        //-------------------------------------------//

        // For Locus
        if (null === $category || in_array('locus', $category)) {
            // Set a specific filter, for this type
            $locusTypeQuery = new \Elastica\Query\Type();
            $locusTypeQuery->setType('locus');

            // Create the BoolQuery, and set a MinNumShouldMatch, to avoid have all results in database
            $locusBoolQuery = new \Elastica\Query\BoolQuery();

            // First, define required queries like: type, security
            //$locusBoolQuery->addFilter($userSecureQuery);
            $locusBoolQuery->addFilter($locusTypeQuery);

            // Then, all conditional queries
            $locusBoolQuery->addShould($keywordQuery);
            $locusBoolQuery->setMinimumShouldMatch(1);

            // Add the Locus BoolQuery to the main BoolQuery
            $query->addShould($locusBoolQuery->setBoost(1));
        }

        // For Feature
        if (null === $category || in_array('feature', $category)) {
            // Set a specific filter, for this type
            $featureTypeQuery = new \Elastica\Query\Type();
            $featureTypeQuery->setType('feature');

            // Create the BoolQuery, and set a MinNumShouldMatch, to avoid have all results in database
            $featureBoolQuery = new \Elastica\Query\BoolQuery();
            $featureBoolQuery->addFilter($featureTypeQuery);

            // First, define required queries like: type, security
            //$locusBoolQuery->addFilter($userSecureQuery);

            // Then, all conditional queries
            $featureBoolQuery->addShould($keywordQuery);
            $featureBoolQuery->setMinimumShouldMatch(1);

            // Add the Locus BoolQuery to the main BoolQuery
            $query->addShould($featureBoolQuery->setBoost(1));
        }

        // For Product
        if (null === $category || in_array('product', $category)) {
            // Set a specific filter, for this type
            $productTypeQuery = new \Elastica\Query\Type();
            $productTypeQuery->setType('product');

            // Create the BoolQuery, and set a MinNumShouldMatch, to avoid have all results in database
            $productBoolQuery = new \Elastica\Query\BoolQuery();
            $productBoolQuery->addFilter($productTypeQuery);

            // First, define required queries like: type, security
            //$locusBoolQuery->addFilter($userSecureQuery);

            // Then, all conditional queries
            $productBoolQuery->addShould($keywordQuery);
            $productBoolQuery->setMinimumShouldMatch(1);

            // Add the Locus BoolQuery to the main BoolQuery
            $query->addShould($productBoolQuery->setBoost(1));
        }

        return $query;
    }
}
